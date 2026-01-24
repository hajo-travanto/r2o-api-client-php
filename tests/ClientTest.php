<?php

declare(strict_types=1);

namespace ready2order\Tests;

/**
 * @internal
 *
 * @coversNothing
 */
class ClientTest extends AbstractTestCase
{
    public function testSettingTimeout(): void
    {
        $client = $this->getApiClient();
        $client->setTimeout(5);

        self::assertSame(5, $client->getTimeout());
    }

    /**
     * @dataProvider languageDataProvider
     *
     * @param mixed $expectedError
     */
    public function testSettingLanguage(string $language, $expectedError): void
    {
        $client = $this->getApiClient();
        $client->setLanguage($language);
        self::expectExceptionMessage($expectedError);
        $client->put('products', []);
    }

    public function languageDataProvider()
    {
        return [
            [
                'de-AT',
                <<<'EOT'
                product name muss ausgefüllt sein.
                product price muss ausgefüllt sein.
                product vat muss angegeben werden, wenn product vat id nicht ausgefüllt wurde.
                product vat id muss angegeben werden, wenn product vat nicht ausgefüllt wurde.
                EOT,
            ],
            [
                'en-US',
                <<<'EOT'
                The product name field is required.
                The product price field is required.
                The product vat field is required when product vat id is not present.
                The product vat id field is required when product vat is not present.
                EOT,
            ],
        ];
    }
}
