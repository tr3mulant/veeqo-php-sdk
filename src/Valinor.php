<?php

namespace TremulantTech\Veeqo;

use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\Mapper\TreeMapper;
use CuyZ\Valinor\MapperBuilder;
use CuyZ\Valinor\Normalizer\Configurator\NormalizeKeysToSnakeCase;
use CuyZ\Valinor\Normalizer\Format;
use CuyZ\Valinor\Normalizer\Normalizer;
use CuyZ\Valinor\NormalizerBuilder;
use DateTimeInterface;
use Saloon\Http\Response;
use TremulantTech\Veeqo\Exceptions\UnexpectedResponse;

/**
 * The one Valinor setup: responses in (ADR 0006/0007/0008), request bodies out (ADR 0010).
 *
 * @internal
 */
final class Valinor
{
    private static ?TreeMapper $mapper = null;

    /** @var Normalizer<array<mixed>>|null */
    private static ?Normalizer $normalizer = null;

    /**
     * @template T of object
     *
     * @param  class-string<T>  $class
     * @return T
     */
    public static function object(Response $response, string $class): object
    {
        try {
            return self::mapper()->map($class, $response->json());
        } catch (MappingError $error) {
            throw new UnexpectedResponse($response, $error);
        }
    }

    /**
     * @template T of object
     *
     * @param  class-string<T>  $class
     * @return list<T>
     */
    public static function list(Response $response, string $class): array
    {
        try {
            /** @var list<T> */
            return self::mapper()->map("list<$class>", $response->json());
        } catch (MappingError $error) {
            throw new UnexpectedResponse($response, $error);
        }
    }

    /**
     * Snake-cased, null-free array of an input Data class.
     *
     * @return array<mixed>
     */
    public static function body(object $data): array
    {
        return (self::$normalizer ??= (new NormalizerBuilder)
            ->configureWith(new NormalizeKeysToSnakeCase)
            ->registerTransformer(fn (DateTimeInterface $date): string => $date->format(DATE_ATOM))
            // Null means omitted: Veeqo can't be sent an explicit null (ADR 0010).
            ->registerTransformer(fn (object $object, callable $next): mixed => is_array($value = $next())
                ? array_filter($value, fn (mixed $v): bool => $v !== null)
                : $value)
            ->normalizer(Format::array())
        )->normalize($data);
    }

    private static function mapper(): TreeMapper
    {
        return self::$mapper ??= (new MapperBuilder)
            // camelCase properties from snake_case keys; a trailing `?` (`is_configured?`) is dropped.
            ->registerKeyConverter(static fn (string $key): string => lcfirst(str_replace('_', '', ucwords(rtrim($key, '?'), '_'))))
            ->allowSuperfluousKeys()
            ->allowUndefinedValues()
            ->supportDateFormats('Y-m-d\TH:i:s.vP', DATE_ATOM)
            // ponytail: money arrives as int|float|string; this also lets an int into any `string` property.
            // Scoping it to numeric-string needs a converter attribute on money properties (ADR 0006 bans attributes).
            // var_export gives a float's shortest round-trip form (ADR 0007); (string) would cut it to 14 digits.
            ->registerConverter(static fn (int|float $value): string => var_export($value, true))
            ->mapper();
    }
}
