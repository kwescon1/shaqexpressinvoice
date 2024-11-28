<?php

declare(strict_types=1);

use App\Utils\InvoiceNumberUtils;
use Illuminate\Support\Carbon;

const FACILITY_CODE = 'ABC';
const FACILITY_BRANCH_CODE = 'XY';

beforeEach(function () {
    // Any setup required before each test
});

dataset('validInvoiceNumbers', function () {
    $facilityCode = 'ABC';
    $facilityBranchCode = 'XY';
    $recId = 'IN';
    $year = Carbon::today()->format('y');

    return [
        'when-number-is-first' => [
            'input' => 'IN'.$year.'-000001',
            'expected' => InvoiceNumberUtils::make($facilityCode, $facilityBranchCode, $recId, '000002'),
        ],
        'when-year-is-same' => [
            'input' => 'IN'.$year.'-000003',
            'expected' => InvoiceNumberUtils::make($facilityCode, $facilityBranchCode, $recId, '000004'),
        ],
        'when-year-differs' => [
            'input' => 'IN21-000003',
            'expected' => InvoiceNumberUtils::make($facilityCode, $facilityBranchCode, $recId, '000001'),
        ],
    ];
});

it('generates invoice numbers correctly', function ($input, $expected) {
    expect(InvoiceNumberUtils::generate(FACILITY_CODE, FACILITY_BRANCH_CODE, $input))
        ->toBe($expected);
})->with('validInvoiceNumbers');

dataset('invalidInvoiceNumbers', [
    'when-uniquerecordnumber-is-strlen-is-less-than-expected' => [
        'input' => 'IN22-00001',
    ],
    'when-uniquerecordnumber-is-strlen-is-more-than-expected' => [
        'input' => 'IN22-0000015',
    ],
    'when-numseq-is-not-a-number' => [
        'input' => 'IN22-FX600100',
    ],
]);

it('throws exception for invalid invoice numbers', function ($input) {
    expect(fn () => InvoiceNumberUtils::generate(FACILITY_CODE, FACILITY_BRANCH_CODE, $input))
        ->toThrow(RuntimeException::class);
})->with('invalidInvoiceNumbers');
