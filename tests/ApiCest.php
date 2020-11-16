<?php

use Codeception\Example;

class ApiCest
{
    /**
     * Проверяем обработку ошибок в негативных сценариях
     *
     * @dataProvider negativeParamsDataProvider
     *
     * @param \ApiTester           $I
     * @param \Codeception\Example $example
     */
    public function negativeParamsTest(ApiTester $I, Example $example): void
    {
        $I->comment("Негативный сценарий для {$example['get']}");

        $I->sendGET($example['get']);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(['error' => 'string:!empty']);
        $I->seeResponseContains(sprintf('"error":"%s -', $example['error']));
    }

    /**
     * Дата провайдер для негативных тестов
     *
     * @return string[][]
     */
    public function negativeParamsDataProvider(): array
    {
        return [
            [
                'get'   => '/?size=1024x1024&croping=3',
                'error' => 'url',
            ],
            [
                'get'   => '/?size=2049x1024&croping=0&url=https%3A%2F%2Fwww.google.com%2F',
                'error' => 'url',
            ],
            [
                'get'   =>
                    '/?url=https%3A%2F%2Fupload.wikimedia.org%2Fwikipedia%2Fcommons%2Fthumb%2Fd%2Fd6%2FManoel.jpg' .
                    '%2F2160px-Manoel.jpg&size=1024x1024',
                'error' => 'url',
            ],
            [
                'get'   =>
                    '/?url=https%3A%2F%2Fjob.kolesa.kz%2Ffiles%2F000%2F000%2F_XUou011.jpg&size=1025x1024&croping=0',
                'error' => 'size',
            ],
            [
                'get'   =>
                    '/?url=https%3A%2F%2Fjob.kolesa.kz%2Ffiles%2F000%2F000%2F_XUou011.jpg&size=256x255&croping=0',
                'error' => 'size',
            ],
            [
                'get'   =>
                    '/?url=https%3A%2F%2Fjob.kolesa.kz%2Ffiles%2F000%2F000%2F_XUou011.jpg&size=256,256&croping=0',
                'error' => 'size',
            ],
            [
                'get'   =>
                    '/?url=https%3A%2F%2Fjob.kolesa.kz%2Ffiles%2F000%2F000%2F_XUou011.jpg&size=1024x1024&croping=3',
                'error' => 'croping',
            ],
            [
                'get'   =>
                    '/?url=https%3A%2F%2Fjob.kolesa.kz%2Ffiles%2F000%2F000%2F_XUou011.jpg&size=1024x1024&croping=-1',
                'error' => 'croping',
            ],
        ];
    }
}
