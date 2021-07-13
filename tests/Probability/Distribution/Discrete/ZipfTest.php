<?php

namespace MathPHP\Tests\Probability\Distribution\Discrete;

use MathPHP\Probability\Distribution\Discrete\Zipf;

class ZipfTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         pmf
     * @dataProvider dataProviderForPmf
     * @param        int    $x
     * @param        number $s
     * @param        int    $N
     * @param        float  $expectedPmf
     *
     * R code to replicate:
     * library(sads)
     * dzipf(x=x, N=N, s=s)
     */
    public function testPmf(int $x, $s, int $N, float $expectedPmf)
    {
        // Given
        $zipf = new Zipf($s, $N);

        // When
        $pmf = $zipf->pmf($x);

        // Then
        $this->assertEqualsWithDelta($expectedPmf, $pmf, 0.001);
    }

    /**
     * @return array [x, s, N, pmf]
     */
    public function dataProviderForPmf(): array
    {
        return [
            [1, 3, 10, 0.8350508],
            [2, 3, 10, 0.1043813],
            [3, 3, 10, 0.03092781],
            [4, 3, 10, 0.01304767],
            [4, 2, 10, 0.04032862],
            [4, 1, 10, 0.08535429],
            [4, 1, 8, 0.09198423],
        ];
    }

    /**
     * @test     pmfthrows a BadDataException if x > N
     * @throws   \Exception
     */
    public function testBadK()
    {
        // Given
        $x    = 11;
        $zipf = new Zipf(3, 10);

        // When
        $pmf = $zipf->pmf($x);

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        $categorical = new Categorical($k, $probabilities);
    }
    /**
     * @test         cdf
     * @dataProvider dataProviderForCdf
     * @param        int    $x
     * @param        number $s
     * @param        int    $N
     * @param        float  $expectedCdf
     *
     * R code to replicate:
     * library(sads)
     * pzipf(q=x, N=N, s=s)
     */
    public function testCdf(int $x, $s, int $N, float $expectedCdf)
    {
        // Given
        $zipf = new Zipf($s, $N);

        // When
        $cdf = $zipf->cdf($x);

        // Then
        $this->assertEqualsWithDelta($expectedCdf, $cdf, 0.001);
    }

    /**
     * @return array[x, s, N, cdf]
     */
    public function dataProviderForCdf(): array
    {
        return [
            [1, 3, 10, 0.8350508],
            [2, 3, 10, 0.9394321],
            [3, 3, 10, 0.9703599],
            [4, 3, 10, 0.9834076],
            [4, 2, 10, 0.9185964],
            [4, 1, 10, 0.7112857],
            [4, 1, 8, 0.7665353],
        ];
    }

    /**
     * @test         mode
     * @dataProvider dataProviderForMode
     * @param        number $s
     * @param        int    $N
     * @param        int    $expected_mode
     */
    public function testMode($s, int $N, int $expected_mode)
    {
        // Given
        $zipf = new Zipf($s, $N);

        // When
        $mode = $zipf->mode();

        // Then
        $this->assertEquals($expected_mode, $mode);
    }

    /**
     * @return array[s, N, mode]
     */
    public function dataProviderForMode(): array
    {
        return [
            [3, 10, 1],
            [2, 10, 1],
            [1, 10, 1],
            [1, 8, 1],
        ];
    }

    /**
     * @test         mean
     * @dataProvider dataProviderForMean
     * @param        number $s
     * @param        int    $N
     * @param        float  $expected_mean
     *
     * R code to replicate:
     * library(sads)
     * x <- 1:N
     * sum(dzipf(x=x, N=N, s=s) * x)
     */
    public function testMean($s, int $N, float $expected_mean)
    {
        // Given
        $zipf = new Zipf($s, $N);

        // When
        $mean = $zipf->mean();

        // Then
        $this->assertEquals($expected_mean, $mean);
    }

    /**
     * @return array[s, N, mean]
     */
    public function dataProviderForMean(): array
    {
        return [
            [3, 10, 1.294135],
            [2, 10, 1.88994],
            [1, 10, 3.414172],
            [1, 8, 2.943495],
        ];
    }
}
