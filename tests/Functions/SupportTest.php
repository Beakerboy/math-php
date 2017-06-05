<?php
namespace MathPHP\Functions;

class SupportTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForCheckLimitsLowerLimit
     */
    public function testCheckLimitsLowerLimit(array $limits, array $params)
    {
        $this->assertTrue(Support::checkLimits($limits, $params));
    }

    public function dataProviderForCheckLimitsLowerLimit()
    {
        return [
            [
                ['x' => '[0,∞]'],
                ['x' => 0],
            ],
            [
                ['x' => '[0,∞]'],
                ['x' => 0.1],
            ],
            [
                ['x' => '[0,∞]'],
                ['x' => 1],
            ],
            [
                ['x' => '[0,∞]'],
                ['x' => 4934],
            ],
            [
                ['x' => '(0,∞]'],
                ['x' => 0.1],
            ],
            [
                ['x' => '(0,∞]'],
                ['x' => 1],
            ],
            [
                ['x' => '(0,∞]'],
                ['x' => 4934],
            ],
            [
                ['x' => '[-50,∞]'],
                ['x' => -50],
            ],
            [
                ['x' => '(-50,∞]'],
                ['x' => -49],
            ],
            [
                ['x' => '[-∞,10]'],
                ['x' => -89379837],
            ],
            [
                ['x' => '(-∞,10]'],
                ['x' => -95893223452],
            ],
            [
                ['x' => '[0,∞]', 'y' => '[0,∞]'],
                ['x' => 0, 'y' => 5],
            ],
            [
                ['x' => '[0,∞]', 'y' => '[0,∞]', 'z' => '[0,1]'],
                ['x' => 0, 'y' => 5],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForCheckLimitsLowerLimitException
     */
    public function testCheckLimitsLowerLimitException(array $limits, array $params)
    {
        $this->setExpectedException('MathPHP\Exception\OutOfBoundsException');
        Support::checkLimits($limits, $params);
    }

    public function dataProviderForCheckLimitsLowerLimitException()
    {
        return [
            [
                ['x' => '[0,∞]'],
                ['x' => -1],
            ],
            [
                ['x' => '[0,∞]'],
                ['x' => -4],
            ],
            [
                ['x' => '[5,∞]'],
                ['x' => 4],
            ],
            [
                ['x' => '(0,∞]'],
                ['x' => -1],
            ],
            [
                ['x' => '(0,∞]'],
                ['x' => -4],
            ],
            [
                ['x' => '(5,∞]'],
                ['x' => 4],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForCheckLimitsUpperLimit
     */
    public function testCheckLimitsUpperLimit(array $limits, array $params)
    {
        $this->assertTrue(Support::checkLimits($limits, $params));
    }

    public function dataProviderForCheckLimitsUpperLimit()
    {
        return [
            [
                ['x' => '[0,5]'],
                ['x' => 0],
            ],
            [
                ['x' => '[0,5]'],
                ['x' => 3],
            ],
            [
                ['x' => '[0,5]'],
                ['x' => 5],
            ],
            [
                ['x' => '[0,5)'],
                ['x' => 0],
            ],
            [
                ['x' => '[0,5)'],
                ['x' => 3],
            ],
            [
                ['x' => '[0,5)'],
                ['x' => 4.999],
            ],
            [
                ['x' => '[0,∞]'],
                ['x' => 9489859893],
            ],
            [
                ['x' => '[0,∞)'],
                ['x' => 9489859893],
            ],
            [
                ['x' => '[0,5]', 'y' => '[0,5]'],
                ['x' => 0],
            ],
            [
                ['x' => '[0,5]', 'y' => '[0,5]'],
                ['x' => 0, 'y' => 3],
            ],
            [
                ['x' => '[0,5]', 'y' => '[0,5]', 'z' => '[0,5]'],
                ['x' => 0, 'y' => 3],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForCheckLimitsUpperLimitException
     */
    public function testCheckLimitsUpperLimitException(array $limits, array $params)
    {
        $this->setExpectedException('MathPHP\Exception\OutOfBoundsException');
        Support::checkLimits($limits, $params);
    }

    public function dataProviderForCheckLimitsUpperLimitException()
    {
        return [
            [
                ['x' => '[0,5]'],
                ['x' => 5.001],
            ],
            [
                ['x' => '[0,5]'],
                ['x' => 6],
            ],
            [
                ['x' => '[0,5]'],
                ['x' => 98349389],
            ],
            [
                ['x' => '[0,5)'],
                ['x' => 5],
            ],
            [
                ['x' => '[0,5)'],
                ['x' => 5.1],
            ],
            [
                ['x' => '[0,5)'],
                ['x' => 857385738],
            ],
        ];
    }

    public function testCheckLimitsLowerLimitEndpointException()
    {
        $this->setExpectedException('MathPHP\Exception\BadDataException');

        $limits = ['x' => '{0,1)'];
        $params = ['x' => 0.5];
        Support::checkLimits($limits, $params);
    }

    public function testCheckLimitsUpperLimitEndpointException()
    {
        $this->setExpectedException('MathPHP\Exception\BadDataException');

        $limits = ['x' => '(0,1}'];
        $params = ['x' => 0.5];
        Support::checkLimits($limits, $params);
    }

    /**
     * @dataProvider dataProviderForCheckLimitsUndefinedParameterException
     */
    public function testCheckLimitsUndefinedParameterException(array $limits, array $params)
    {
        $this->setExpectedException('MathPHP\Exception\BadParameterException');
        Support::checkLimits($limits, $params);
    }

    public function dataProviderForCheckLimitsUndefinedParameterException()
    {
        return [
            [
                ['x' => '[0,1]'],
                ['y' => 0.5],
            ],
            [
                ['x' => '[0,1]', 'a' => '[0,10]'],
                ['y' => 0.5],
            ],
            [
                ['x' => '[0,1]', 'a' => '[0,10]'],
                ['x' => 0.5, 'b' => 4],
            ],
            [
                ['x' => '[0,1]', 'a' => '[0,10]'],
                ['x' => 0.5, 'a' => 4, 'z' => 9],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForLanczosConstants
     */
    public function testLanczosConstants(int $n, $g, array $expected)
    {
        $calculated = Support::lanczosConstants($n, $g);
        foreach ($calculated as $key => $value) {
            $diff = abs(.01 * $expected[$key]);
            $this->assertEquals($expected[$key], $value, '', $diff);
        }
    }

    public function dataProviderForLanczosConstants()
    {
        return [
            [// https://mrob.com/pub/ries/lanczos-gamma.html
                6,
                5,
                [
                    1.000000000190015,
                    76.18009172947146,
                    -86.50532032941677,
                    24.01409824083091,
                    -1.231739572450155,
                    0.1208650973866179e-2,
                    -0.5395239384953e-5,
                ],
            ],
            [
                9,
                7,
                [
                    0.99999999999980993227684700473478,
                    676.520368121885098567009190444019,
                    -1259.13921672240287047156078755283,
                    771.3234287776530788486528258894,
                    -176.61502916214059906584551354,
                    12.507343278686904814458936853,
                    -0.13857109526572011689554707,
                    9.984369578019570859563e-6,
                    1.50563273514931155834e-7,
                ],
            ],
            [
                11,
                9,
                [
                    1.000000000000000174663,
                    5716.400188274341379136,
                    -14815.30426768413909044,
                    14291.49277657478554025,
                    -6348.160217641458813289,
                    1301.608286058321874105,
                    -108.1767053514369634679,
                    2.605696505611755827729,
                    -0.7423452510201416151527e-2,
                    0.5384136432509564062961e-7,
                    -0.4023533141268236372067e-8,
                ],
            ],
            [
                15,
                4.7421875,
                [
                    0.99999999999999709182,
                    57.156235665862923517,
                    -59.597960355475491248,
                    14.136097974741747174,
                    -0.49191381609762019978,
                    .33994649984811888699e-4,
                    .46523628927048575665e-4,
                    -.98374475304879564677e-4,
                    .15808870322491248884e-3,
                    -.21026444172410488319e-3,
                    .21743961811521264320e-3,
                    -.16431810653676389022e-3,
                    .84418223983852743293e-4,
                    -.26190838401581408670e-4,
                    .36899182659531622704e-5
                ],
            ],
        ];
    }
}
