<?php

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use TremulantTech\Veeqo\Data\CurrentCompany\UpdateCurrentCompanyData;
use TremulantTech\Veeqo\Enums\DimensionsUnit;
use TremulantTech\Veeqo\Requests\CurrentCompany\GetCurrentCompany;
use TremulantTech\Veeqo\Requests\CurrentCompany\UpdateCurrentCompany;
use TremulantTech\Veeqo\Responses\Company;
use TremulantTech\Veeqo\Responses\User;

it('gets the current company as a typed Company', function () {
    $mock = new MockClient([GetCurrentCompany::class => fixtureResponse('responses/current_company.show')]);

    $company = veeqo($mock)->currentCompany()->get();

    expect($company)->toBeInstanceOf(Company::class)
        ->and($company->id)->toBe(1004)
        ->and($company->trialing)->toBeFalse()
        ->and($company->shopifyAppStoreReferral)->toBeFalse()
        ->and($company->trialEndDate?->format(DATE_ATOM))->toBe('2020-01-01T00:00:00+00:00')
        ->and($company->planFeatures['api']['any_actions'])->toBeTrue()
        ->and($company->paidPlanFeatures['CHANNEL'][0]->condition->limit ?? null)->toBe(10)
        ->and($company->owner)->toBeInstanceOf(User::class)
        ->and($company->owner->roleId)->toBe(1011)
        ->and($company->employees)->toHaveCount(1)
        ->and($company->companyAddress->addressLine1)->toBe('redacted-634fc388')
        ->and($company->settings->dimensionsUnit)->toBe(DimensionsUnit::Inches);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/current_company'
        && $response->getPsrRequest()->getMethod() === 'GET');
});

it('updates the current company unenveloped, returning nothing on the 204', function () {
    $mock = new MockClient([UpdateCurrentCompany::class => MockResponse::make(status: 204)]);

    $result = veeqo($mock)->currentCompany()->update(new UpdateCurrentCompanyData(name: 'ACME'));

    expect($result)->toBeNull();
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/current_company'
        && $response->getPsrRequest()->getMethod() === 'PUT'
        && (string) $response->getPsrRequest()->getBody() === '{"name":"ACME"}');
});
