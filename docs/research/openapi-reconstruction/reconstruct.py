#!/usr/bin/env python3
"""Rebuild an OpenAPI 3.0 document from developers.veeqo.com's rendered pages.

Research asset for issue #3, not production tooling. It inverts the
starlight-openapi 0.19.x renderer (components/schema/*.astro, Items.astro, Key.astro).

  python3 reconstruct.py extract-embedded <api-schemas page.html>  > embedded.json
  python3 reconstruct.py rebuild <operation page.html>...           > reconstructed.json
  python3 reconstruct.py selftest <embedded.json> <ops dir>   # round-trip check

Needs beautifulsoup4 + lxml.
"""
import json
import re
import sys
from pathlib import Path

from bs4 import BeautifulSoup, Tag


def kids(el):
    return [c for c in el.children if isinstance(c, Tag)]


def classes(el):
    return el.get("class") or []


def text(el):
    return re.sub(r"\s+", " ", el.get_text(" ", strip=True)).strip()


def code_value(fig_parent):
    btn = fig_parent.select_one("button[data-code]")
    if btn is None:
        return None
    # bs4 has already decoded entities; Expressive Code joins lines with U+007F.
    return btn["data-code"].replace("\x7f", "\n")


def coerce(raw, schema):
    t = schema.get("type")
    try:
        if t in ("object", "array") or raw[:1] in "[{":
            return json.loads(raw)
        if t == "integer":
            return int(raw)
        if t == "number":
            return float(raw) if "." in raw else int(raw)
        if t == "boolean":
            return raw == "true"
    except ValueError:
        pass
    return raw


def parse_type(t):
    m = re.fullmatch(r"Array<(.+)>", t)
    if m:
        return {"type": "array", "items": parse_type(m.group(1))}
    if t == "Array":
        return {"type": "array", "items": {}}
    if " | " in t:
        return {"type": t.split(" | ")}
    return {"type": t}


def apply_tags(div, s):
    label = div.find(string=True, recursive=False)
    values = [text(sp) for sp in div.find_all("span", recursive=False)]
    if label and "Allowed values" in label:
        target = s["items"] if s.get("type") == "array" and "items" in s else s
        target["enum"] = [coerce(v, target) for v in values]
        return
    for v in values:
        if v == "nullable":
            s["nullable"] = True
        elif v.startswith("default: "):
            s["default"] = coerce(v[9:], s)
        elif m := re.fullmatch(r"(>=?|<=?) (-?[\d.]+)$", v):
            op, n = m.groups()
            n = float(n) if "." in n else int(n)
            key = "minimum" if op[0] == ">" else "maximum"
            s[key] = n
            if "=" not in op:
                s["exclusive" + key.capitalize()] = True
        elif m := re.fullmatch(r"(>=|<=) (\d+) (characters|items|properties)", v):
            op, n, unit = m.groups()
            pre = "min" if op == ">=" else "max"
            s[pre + {"characters": "Length", "items": "Items", "properties": "Properties"}[unit]] = int(n)
        elif v.startswith("/") and v.endswith("/"):
            s["pattern"] = v[1:-1]
        elif v.startswith("multiple of "):
            s["multipleOf"] = float(v[12:])
        elif v == "unique items":
            s["uniqueItems"] = True
        else:
            s.setdefault("x-unparsed-tags", []).append(v)


def parse_object(details):
    o = {"type": "object", "properties": {}}
    required = []
    for c in kids(details):
        if c.name == "summary":
            continue
        if "key" in classes(c):
            name_div = c.find(class_="name")
            name = text(name_div.find("strong"))
            desc = next(d for d in kids(c) if "description" in classes(d))
            if name_div.find(class_="additional"):
                o["additionalProperties"] = True if desc.find(class_="any") else parse_schema(desc)
                continue
            prop = parse_schema(desc)
            if name_div.find(class_="required"):
                required.append(name)
            if name_div.find(class_="deprecated"):
                prop["deprecated"] = True
            o["properties"][name] = prop
        elif "tags" in classes(c):
            apply_tags(c, o)
    if required:
        o["required"] = required
    if not o["properties"]:
        del o["properties"]
    return o


def parse_schema(container):
    s = {}
    nodes = kids(container)
    i = 0
    while i < len(nodes):
        c = nodes[i]
        cls = classes(c)
        if c.name == "em":
            s["title"] = text(c)
        elif c.name == "details":
            obj = parse_object(c)
            if s.get("type") == "array":
                s["items"] = {**s.get("items", {}), **obj}
            else:
                s.update(obj)
        elif c.name == "h5":
            pass  # "Example"/"Examples" heading; the value follows
        elif "expressive-code" in cls or c.select_one(":scope > .expressive-code"):
            raw = code_value(c)
            if raw is not None:
                s["example"] = coerce(raw, s)
        elif "tags" in cls:
            apply_tags(c, s)
        elif c.name == "starlight-tabs":
            pass  # consumed by the "One of:" branch
        elif c.name == "div" and (ty := c.find("span", class_="type", recursive=False)):
            t = text(ty)
            if t in ("One of:", "Any of:"):
                key = "oneOf" if t == "One of:" else "anyOf"
                tabs = c.find_next_sibling("starlight-tabs")
                s[key] = [parse_schema(p) for p in tabs.select(":scope > div[role=tabpanel]")]
                if disc := c.find(string=re.compile("discriminator: ")):
                    s["discriminator"] = {"propertyName": disc.split(": ", 1)[1].strip()}
            else:
                if t.startswith("not "):
                    s["not"] = parse_type(t[4:])
                else:
                    s.update(parse_type(t))
                for tag in c.find_all("span", recursive=False):
                    if (v := text(tag)).startswith("format: "):
                        (s["items"] if s.get("type") == "array" and s["items"].get("type") else s)["format"] = v[8:]
                if any(text(t2) == "recursive" for t2 in c.find_all("span", recursive=False)):
                    s["x-recursive"] = True
        elif c.name == "div":
            md = text(c)
            if md:
                s["description"] = md
        i += 1
    return s


def parse_media(picker):
    content = {}
    for panel in picker.select(':scope > [role="tabpanel"]'):
        ct = panel["data-openapi-content-type"]
        content[ct] = {"schema": parse_schema(panel)} if kids(panel) else {}
    return content


def own_keys(section):
    """Parameter/header keys belonging to this section, not nested in schemas."""
    return [k for k in section.select(".key") if k.find_parent("details") is None
            and k.find_parent("section") is section]


def parse_param(key, location):
    name_div = key.find(class_="name")
    p = {"name": text(name_div.find("strong")), "in": location}
    if name_div.find(class_="required") or location == "path":
        p["required"] = True
    schema = parse_schema(next(d for d in kids(key) if "description" in classes(d)))
    if "description" in schema:
        p["description"] = schema.pop("description")
    if "example" in schema:
        p["example"] = schema.pop("example")
    p["schema"] = schema
    return p


def parse_examples(section):
    out = {}
    for h in section.find_all("h5"):
        if text(h) != "Examples":
            continue
        for fig in h.find_next_siblings():
            for f in fig.select(".expressive-code") if "expressive-code" not in classes(fig) else [fig]:
                raw = code_value(f)
                cap = f.find("figcaption")
                name = text(cap) or f"example{len(out) + 1}"
                try:
                    out[name] = {"value": json.loads(raw)}
                except (TypeError, ValueError):
                    out[name] = {"value": raw}
    return out


def parse_operation(path):
    soup = BeautifulSoup(Path(path).read_text(), "lxml")
    main = soup.select_one(".sl-markdown-content")
    if main.find("details") is None:
        return None  # tag overview page, not an operation
    head = main.find("details").find("summary").select("div > div")
    method, route = text(head[0]).lower(), text(head[1])
    op = {"summary": text(soup.find("h1")), "x-docs-slug": Path(path).stem}
    desc = [text(d) for d in kids(main) if d.name == "div" and d.find("p", recursive=False)
            and not d.find("section")]
    if desc:
        op["description"] = "\n\n".join(desc)
    params = []
    for sec in main.find_all("section"):
        h = sec.find(["h2", "h3", "h4"])
        hid = h.get("id", "") if h else ""
        if hid.endswith("-parameters"):
            params += [parse_param(k, hid[: -len("-parameters")]) for k in own_keys(sec)]
        elif hid == "request-body":
            picker = sec.find("starlight-openapi-content-picker")
            op["requestBody"] = {"content": parse_media(picker) if picker else {}}
        elif h and h.name == "h3" and sec.find_parent("section") is None and (hid.isdigit() or hid == "default"):
            r = {"description": ""}
            d = sec.find("div", recursive=False)
            if d is not None and d.find("p"):
                r["description"] = text(d)
            hs = next((s for s in sec.find_all("section") if s.find("h4", id=re.compile("^headers"))), None)
            if hs:
                r["headers"] = {p["name"]: {k: v for k, v in p.items() if k not in ("name", "in", "required")}
                                for p in (parse_param(k, "header") for k in own_keys(hs))}
            picker = sec.find("starlight-openapi-content-picker")
            if picker is not None and picker.select(':scope > [role="tabpanel"]'):
                r["content"] = parse_media(picker)
                ex = parse_examples(sec)
                if ex:
                    for media in r["content"].values():
                        media["examples"] = ex
            op.setdefault("responses", {})[hid] = r
    if params:
        op["parameters"] = params
    return route, method, op


def rebuild(files):
    doc = {"openapi": "3.0.0",
           "info": {"title": "Veeqo API (reconstructed from developers.veeqo.com)", "version": "1.0.0"},
           "servers": [{"url": "https://api.veeqo.com"}], "paths": {}}
    for f in files:
        parsed = parse_operation(f)
        if parsed is None:
            continue
        route, method, op = parsed
        doc["paths"].setdefault(route, {})[method] = op
    return doc


def extract_embedded(page):
    soup = BeautifulSoup(Path(page).read_text(), "lxml")
    return json.loads(code_value(soup.select_one(".expressive-code")))


def strip(s):
    """Drop what the renderer cannot carry, so round-trips compare like with like."""
    if isinstance(s, dict):
        out = {}
        for k, v in s.items():
            if k in ("example", "examples", "description", "x-docs-slug", "summary", "operationId", "tags",
                     "decsription"):
                continue
            if k == "required" and isinstance(v, list):
                v = sorted(n for n in v if n in s.get("properties", {}))
                if not v:
                    continue
            if k == "default" and v in (0, False, ""):
                continue  # Items.astro renders `items.default && ...`
            if k == "nullable" and (s.get("type") == "object" or "properties" in s):
                continue  # SchemaObjectObject.astro never renders nullable
            if k == "required" and v is True:
                continue  # RequestBody.astro never renders requestBody.required
            if k == "content" and not any(m.get("schema") for m in v.values()):
                continue  # example-only or empty media types render no schema
            out[k] = strip(v)
        if out.get("type") == "array" and isinstance(out.get("items"), dict) and not out["items"].get("type") \
                and "properties" not in out["items"]:
            out["items"] = {}  # Array<untyped> renders as plain "Array"
        return out
    if isinstance(s, list):
        return [strip(x) for x in s]
    return s


def diff(a, b, path=""):
    if type(a) is not type(b):
        yield f"{path}: {json.dumps(a)[:80]} != {json.dumps(b)[:80]}"
    elif isinstance(a, dict):
        for k in sorted(set(a) | set(b)):
            if k not in a:
                yield f"{path}/{k}: only in source"
            elif k not in b:
                yield f"{path}/{k}: only in rebuilt"
            else:
                yield from diff(a[k], b[k], f"{path}/{k}")
    elif isinstance(a, list):
        if len(a) != len(b):
            yield f"{path}: list len {len(a)} != {len(b)}"
        for i, (x, y) in enumerate(zip(a, b)):
            yield from diff(x, y, f"{path}/{i}")
    elif a != b:
        yield f"{path}: {a!r} != {b!r}"


def selftest(embedded_path, ops_dir):
    """Round-trip: for operations present in the embedded spec, rebuilt == source (minus renderer losses)."""
    src = json.loads(Path(embedded_path).read_text())
    rebuilt = rebuild(sorted(Path(ops_dir).glob("*.html")))
    compared = mismatched = 0
    for route, item in src["paths"].items():
        for method, op in item.items():
            if method not in ("get", "post", "put", "delete", "patch"):
                continue
            got = rebuilt["paths"].get(route, {}).get(method)
            if got is None:
                print(f"MISSING {method.upper()} {route}")
                mismatched += 1
                continue
            compared += 1
            for part in ("requestBody", "responses"):
                a = strip(got.get(part)) if got.get(part) else None
                b = strip(op.get(part)) if op.get(part) else None
                if part == "responses" and b:
                    for r in b.values():
                        r.pop("headers", None)
                    for r in (a or {}).values():
                        r.pop("headers", None)
                d = list(diff(a, b, f"{method.upper()} {route} {part}"))
                if d:
                    mismatched += 1
                    print("\n".join(d[:15]))
    print(f"compared {compared} operations, {mismatched} with differences")
    return mismatched


if __name__ == "__main__":
    cmd, *args = sys.argv[1:]
    if cmd == "extract-embedded":
        print(json.dumps(extract_embedded(args[0]), indent=2))
    elif cmd == "rebuild":
        print(json.dumps(rebuild(args), indent=2))
    elif cmd == "selftest":
        sys.exit(1 if selftest(*args) else 0)
