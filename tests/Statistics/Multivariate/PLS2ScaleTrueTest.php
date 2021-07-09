<?php

namespace MathPHP\Tests\Statistics\Multivariate;

use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\SampleData;
use MathPHP\Statistics\Multivariate\PLS;

class PLS2ScaleTrueTest extends \PHPUnit\Framework\TestCase
{
    /** @var PLS */
    private static $pls;

    /** @var Matrix */
    private static $X;

    /** @var Matrix */
    private static $Y;

    /**
     * R code for expected values:
     *   library(chemometrics)
     *   data(cereal)
     *   pls.model = pls2_nipals(cereal$X, cereal$Y, a=5, scale=TRUE)
     *
     * @throws Exception\MathException
     */
    public static function setUpBeforeClass(): void
    {
        $cereal   = new SampleData\Cereal();
        self::$X  = MatrixFactory::create($cereal->getXData());
        self::$Y  = MatrixFactory::create($cereal->getYData());

        self::$pls = new PLS(self::$X, self::$Y, 5, true);
    }

    /**
     * @test         Construction
     * @throws       Exception\MathException
     */
    public function testConstruction()
    {
        // When
        $pls = new PLS(self::$X, self::$Y, 5, true);

        // Then
        $this->assertInstanceOf(PLS::class, $pls);
    }

    /**
     * @test Construction error - row mismatch
     */
    public function testConstructionFailureXAndYRowMismatch()
    {
        // Given
        $Y = self::$Y->rowExclude(0);

        // Then
        $this->expectException(\MathPHP\Exception\BadDataException::class);

        // When
        $pls = new PLS(self::$X, $Y, 2, true);
    }

    /**
     * @test The class returns the correct values for B
     *
     * R code for expected values:
     *   pls.model$B
     */
    public function testB()
    {
        // Given
        $expected = [
            [0.0424707069, 0.01922291, -0.0099608045, -0.0246390484, 0.02306057, -0.02697744],
            [0.0385832774, 0.01614914, -0.0062902995, -0.0227350397, 0.02341202, -0.02745547],
            [0.0364120503, 0.01354773, -0.0030920415, -0.020133587, 0.02361091, -0.02770132],
            [0.0354391468, 0.01204776, -0.0011469204, -0.0178106395, 0.02346253, -0.02743984],
            [0.036031669, 0.01139083, 0.000206726, -0.015767197, 0.02354168, -0.02749393],
            [0.0380948985, 0.01176063, 0.0005425224, -0.0149185035, 0.02399636, -0.02809532],
            [0.0426980856, 0.01569193, -0.0027563096, -0.017198764, 0.02434669, -0.02856139],
            [0.0524814205, 0.02560192, -0.0120915331, -0.0242491449, 0.02459862, -0.02891174],
            [0.0663227203, 0.04256282, -0.0300506637, -0.0387513507, 0.02396341, -0.02812098],
            [0.1130840159, 0.08504567, -0.0931212385, -0.1083733388, 0.02959551, -0.03949878],
            [-0.0484467113, -0.0358201, 0.0127986841, 0.0083370029, -0.01452482, 0.01396074],
            [-0.0609139848, -0.04971552, 0.0285734313, 0.0228254672, -0.01502579, 0.01476183],
            [-0.0639778879, -0.05856773, 0.0379404218, 0.0281332879, -0.01221045, 0.01075142],
            [-0.0613144204, -0.06302223, 0.0424998608, 0.0287714841, -0.008505338, 0.005436208],
            [-0.0518147647, -0.06408869, 0.0434166149, 0.0250382285, -0.002888487, -0.002626654],
            [-0.0326098073, -0.06022222, 0.0395146671, 0.0161723212, 0.00515197, -0.01411011],
            [-0.0036567904, -0.05559908, 0.0358419592, 0.005747735, 0.01736343, -0.03136755],
            [0.0217058116, -0.05177983, 0.032865107, -0.0013307204, 0.02726596, -0.0451443],
            [0.0229111624, -0.05339373, 0.0310132411, -0.0012962145, 0.02663878, -0.04384753],
            [0.0171663558, -0.05875347, 0.0303055542, 0.0008268606, 0.02389102, -0.03899036],
            [0.01966669, -0.05119482, 0.018896012, -0.0048430991, 0.02170872, -0.03268775],
            [0.0140654092, -0.02221532, 0.0009706415, -0.0120137483, 0.01385693, -0.01671632],
            [0.010535322, -0.003627285, -0.0060423234, -0.011935296, 0.008687775, -0.00747213],
            [0.010961272, 0.003932709, -0.0071171294, -0.0100341791, 0.007322952, -0.004889809],
            [0.0124638893, 0.008596039, -0.008040955, -0.0084224507, 0.006402567, -0.003277768],
            [0.0137508177, 0.01267921, -0.0100774818, -0.0072434666, 0.004816007, -0.0008347483],
            [0.0157145169, 0.01669081, -0.0127999332, -0.0065162183, 0.00318265, 0.001624437],
            [0.0182058402, 0.02063483, -0.0158069087, -0.006369192, 0.001809936, 0.003677814],
            [0.0214296591, 0.02408225, -0.0185838687, -0.0069594836, 0.001206302, 0.004596716],
            [0.023767112, 0.02613047, -0.0206776682, -0.0075820233, 0.0008058164, 0.005178105],
            [0.0229797603, 0.02545171, -0.0210337277, -0.0071953361, 0.00008875932, 0.006153056],
            [0.0177505842, 0.02187471, -0.0193941552, -0.0055691676, -0.001294524, 0.008035667],
            [0.0111067768, 0.01780601, -0.0170519324, -0.0036202706, -0.002792021, 0.01010244],
            [0.0056193324, 0.01437137, -0.0143762161, -0.0015491993, -0.003734884, 0.01143621],
            [0.0014433131, 0.01098292, -0.010912976, 0.0005101963, -0.003694139, 0.01140126],
            [-0.0009658682, 0.007862246, -0.0071448875, 0.0018486459, -0.002499802, 0.009729503],
            [-0.00271819, 0.004466526, -0.0029141211, 0.0028047425, -0.0005751842, 0.007008302],
            [-0.0053244036, 0.00002009668, 0.0021465119, 0.004170236, 0.001436816, 0.004138812],
            [-0.009332644, -0.00568251, 0.007900804, 0.0060598075, 0.003203322, 0.001582311],
            [-0.0152812229, -0.01242535, 0.0135623073, 0.0080369906, 0.004227321, 0.00002506987],
            [-0.0231033987, -0.01876942, 0.0170397458, 0.0096353031, 0.003303912, 0.001171181],
            [-0.0321356479, -0.0234367, 0.0162742961, 0.0114199585, -0.001128461, 0.00719909],
            [-0.031857544, -0.02086623, 0.0046165996, 0.0121505454, -0.009959724, 0.01905063],
            [0.0344142458, 0.0217712, -0.0494873944, -0.0023419664, -0.02295188, 0.03168389],
            [0.0540900829, 0.02867154, -0.0351225619, -0.0092794442, -0.006106874, 0.003136351],
            [0.04584578, 0.01746976, -0.017360977, -0.0060625768, 0.001804035, -0.008452304],
            [0.036460344, 0.006232529, -0.0040015298, -0.0040311588, 0.007058878, -0.01609298],
            [0.027439001, -0.003357619, 0.0055706798, -0.0058938016, 0.01185213, -0.02310077],
            [0.0207556172, -0.008586282, 0.009688652, -0.0121937208, 0.01592161, -0.02906739],
            [0.0157994036, -0.01224079, 0.012418025, -0.0200891262, 0.02044476, -0.03561466],
            [0.0095879996, -0.01549631, 0.0143241612, -0.028126949, 0.02410488, -0.04087916],
            [0.0031835289, -0.01692899, 0.014053114, -0.0351342475, 0.02564956, -0.0430837],
            [0.0001575035, -0.01663872, 0.0125261149, -0.0405776565, 0.0265501, -0.0443448],
            [0.0004019924, -0.01251035, 0.0074355888, -0.0429053335, 0.02428883, -0.04111863],
            [0.0006625679, -0.006774399, 0.0001641066, -0.0394389983, 0.01735717, -0.03132428],
            [0.0023076336, -0.001513142, -0.005897658, -0.030634203, 0.00889008, -0.01929324],
            [0.001959778, -0.000005505417, -0.0068100374, -0.0195135071, 0.002248015, -0.00974126],
            [-0.0019227169, -0.001588161, -0.005212321, -0.0114506757, -0.002105773, -0.003463192],
            [-0.0063119507, -0.003740936, -0.003068313, -0.0050610807, -0.005341753, 0.001221099],
            [-0.009752691, -0.006116652, -0.0005934806, -0.0002372117, -0.007220133, 0.003955037],
            [-0.0130379072, -0.007241091, 0.0002701054, 0.0028346833, -0.009258931, 0.006899895],
            [-0.0151823401, -0.006052894, -0.0015116911, 0.0042142962, -0.01190283, 0.01070021],
            [-0.015388405, -0.002260015, -0.0060662801, 0.0040476838, -0.01506253, 0.01524088],
            [-0.0139662662, 0.005870783, -0.0152827558, 0.0026408233, -0.0203089, 0.02279853],
            [-0.0085620889, 0.02125926, -0.0319012333, -0.0005439297, -0.02851153, 0.03467493],
            [0.0041562462, 0.04751246, -0.0583673121, -0.0029755801, -0.04158509, 0.05380726],
            [0.0296137955, 0.08717532, -0.0949252162, 0.0030548505, -0.06176666, 0.08397508],
            [0.089396077, 0.1479714, -0.1412356123, 0.0255540318, -0.08425287, 0.1217595],
            [0.0913743204, 0.09061738, -0.0706298719, 0.0206588037, -0.02810142, 0.04689808],
            [0.0805515598, 0.05867674, -0.0376422784, 0.0053395757, 0.00008965576, 0.006950319],
            [0.0788570589, 0.05334233, -0.0326574003, -0.0051376188, 0.008607103, -0.005290012],
            [0.0781045421, 0.05534266, -0.0353980725, -0.0097402462, 0.00887196, -0.005709375],
            [0.0731337249, 0.05573402, -0.0370482225, -0.0067101221, 0.00454962, 0.0004695299],
            [0.0426868068, 0.03105637, -0.0170661024, 0.0157459702, -0.002922253, 0.01100431],
            [-0.0363039964, -0.04666242, 0.0477688973, 0.0612503357, -0.006745349, 0.01545068],
            [-0.1098800844, -0.1287163, 0.116093327, 0.0844712113, 0.005096696, -0.003092674],
            [-0.1230528136, -0.1449078, 0.1256284825, 0.0613907794, 0.0196569, -0.02454816],
            [-0.1026614709, -0.1179455, 0.0953597679, 0.0188762197, 0.02729084, -0.03566958],
            [-0.07945022, -0.0839135, 0.0593492491, -0.0136590006, 0.02647841, -0.03464704],
            [-0.0781875645, -0.06278186, 0.0301859911, -0.0335059456, 0.01661981, -0.02118479],
            [-0.1679126755, -0.08891839, 0.0184067294, -0.0273487466, -0.02553835, 0.03437434],
            [-0.1323899186, -0.05817729, 0.0081921603, 0.0204742659, -0.0501596, 0.06504298],
            [-0.0736679786, -0.02577699, -0.0029607899, 0.0232610528, -0.04120825, 0.05247609],
            [-0.0478734918, -0.006504129, -0.0153813379, 0.0171882359, -0.03861017, 0.04893342],
            [-0.0329875668, 0.007394348, -0.0272032825, 0.0084461414, -0.03761704, 0.04754185],
            [-0.0234747212, 0.01237367, -0.0314365286, 0.0013920999, -0.03390528, 0.04216221],
            [-0.0201412847, 0.0110648, -0.0305847337, -0.003820714, -0.02955858, 0.03580699],
            [-0.0249522354, 0.005368351, -0.0268959376, -0.0052250316, -0.02778983, 0.03312244],
            [-0.0382386819, -0.005362983, -0.0205800657, 0.0001827897, -0.03038198, 0.03673796],
            [-0.0627308312, -0.02042403, -0.0190438248, 0.0118356744, -0.04269793, 0.05479171],
            [-0.0702445869, -0.02566651, -0.0147615075, 0.0221659188, -0.04235345, 0.06258855],
            [-0.0316340551, -0.007312619, -0.0093762874, 0.011187637, -0.01912202, 0.03233529],
            [-0.0212362843, -0.0004395765, -0.010580644, 0.0078585216, -0.01472098, 0.02652803],
            [-0.020236053, 0.001589556, -0.0112394943, 0.0076219081, -0.01467796, 0.026614],
            [-0.0228045567, 0.001209644, -0.0107419605, 0.0085047727, -0.01557662, 0.02795328],
            [-0.0263707326, -0.001254622, -0.0083913524, 0.0100415143, -0.01584985, 0.02836159],
            [-0.0301673622, -0.005674606, -0.004023615, 0.0119963327, -0.01501031, 0.02715721],
            [-0.0341327891, -0.01201236, 0.0024419656, 0.0141551563, -0.01286609, 0.0240721],
            [-0.0381840376, -0.02050794, 0.0113002309, 0.0167081497, -0.009287901, 0.01891705],
            [-0.0420590721, -0.03222214, 0.0237904109, 0.0204801615, -0.003768743, 0.01095452],
            [-0.0451264227, -0.05191896, 0.0454147641, 0.0282162798, 0.006271009, -0.003547005],
            [-0.0461213465, -0.112963, 0.1152035585, 0.0606712549, 0.0373597, -0.04862881],
            [0.0302312647, -0.1066912, 0.1334221973, 0.072512267, 0.06157589, -0.09287192],
            [0.0384648395, -0.02566599, 0.04341297, 0.029606372, 0.02335308, -0.03874714],
            [0.0374872551, -0.01190121, 0.0280891021, 0.0229669293, 0.0158005, -0.02794205],
            [0.0366721965, -0.007135139, 0.0229437132, 0.0187498402, 0.01416193, -0.02560123],
            [0.0352597158, -0.003343914, 0.0184498924, 0.0128852695, 0.01365625, -0.02491192],
            [0.0343412444, 0.001261142, 0.0131510701, 0.0046687357, 0.01399473, -0.02542058],
            [0.0334892641, 0.004818475, 0.0097140457, -0.0038500289, 0.01586555, -0.0280348],
            [0.0305803385, 0.003528362, 0.0129822955, -0.0085920354, 0.01989784, -0.03353723],
            [0.0239332893, -0.006427991, 0.0280056301, -0.0048780718, 0.02673348, -0.04264846],
            [0.0110382441, -0.02738508, 0.0582798276, 0.0110893368, 0.03634582, -0.05478175],
            [-0.0084329158, -0.04976852, 0.0889460205, 0.0312401383, 0.04285227, -0.0610314],
            [-0.0305647241, -0.0510179, 0.0843399928, 0.0318280305, 0.03432846, -0.04576653],
            [-0.0406008177, -0.02847526, 0.0473869817, 0.0093631648, 0.01818393, -0.02128936],
            [-0.0388913916, -0.002432208, 0.0088730086, -0.020914457, 0.008012965, -0.006561879],
            [-0.0353680164, 0.01253145, -0.0146217551, -0.0465891909, 0.006128369, -0.004253771],
            [-0.0355437111, 0.01664098, -0.0243612936, -0.0639929948, 0.008202887, -0.007798533],
            [-0.0387621149, 0.0146341, -0.0267615181, -0.0762238029, 0.01165587, -0.0134141],
            [-0.0324808146, 0.01775386, -0.0327231958, -0.092460958, 0.01758818, -0.02275326],
            [-0.0175005735, 0.03858259, -0.0560821362, -0.1198980608, 0.01922385, -0.02711081],
            [0.0201438196, 0.08801335, -0.1023438014, -0.110587876, -0.009676515, 0.00914035],
            [0.0389884193, 0.06517794, -0.0640650122, -0.0226570253, -0.02417734, 0.02921108],
            [0.0373588666, 0.04003488, -0.0309850242, 0.0147659134, -0.02110795, 0.0253053],
            [0.0332859541, 0.0214153, -0.0072791171, 0.0352272272, -0.01645342, 0.01896813],
            [0.0286457109, 0.007231704, 0.0095286149, 0.0476578199, -0.01262332, 0.01365495],
            [0.0187405971, -0.005323096, 0.0223320074, 0.0538538895, -0.01003899, 0.009934844],
            [0.0060325068, -0.008282277, 0.0229140781, 0.0514884631, -0.01244689, 0.01321014],
            [-0.0076803476, -0.005612253, 0.0162739611, 0.0434506966, -0.01726364, 0.01985935],
            [-0.0177613179, -0.003813483, 0.0111011468, 0.0344622643, -0.01940306, 0.02268148],
            [-0.0275754002, 0.0004169249, 0.0040497627, 0.0248070204, -0.02237129, 0.02679732],
            [-0.0351863686, 0.005143324, -0.0022102013, 0.0168637575, -0.02493442, 0.03047075],
            [-0.0444536783, 0.009373969, -0.0087092407, 0.0097452976, -0.02859202, 0.03574437],
            [-0.0641548221, 0.01400439, -0.0164728721, 0.0042068435, -0.03711348, 0.0482117],
            [-0.0821886374, 0.01903146, -0.0208441145, -0.0032516444, -0.04134829, 0.05522189],
            [-0.0829733769, 0.03283739, -0.0283615851, -0.0204651512, -0.03620886, 0.05053272],
            [-0.0276211156, 0.02148702, -0.0008368395, -0.0307624152, 0.007754776, -0.009293935],
            [0.0093997413, 0.001353133, 0.0294720358, -0.026018022, 0.0387182, -0.05259773],
            [0.023393017, -0.005950795, 0.0429679183, -0.0180893313, 0.04819917, -0.06635605],
            [0.0213445152, -0.01320515, 0.0510478548, -0.013563783, 0.05018468, -0.07022645],
            [-0.005011296, -0.01802805, 0.0496889452, -0.0080537364, 0.03698175, -0.05311701],
            [-0.0340241027, -0.01352357, 0.0339782771, -0.0029462209, 0.01284482, -0.02082797],
            [-0.0549823591, -0.004994418, 0.0123338641, 0.0026259519, -0.01279174, 0.01416522],
            [-0.0539707672, 0.001712832, -0.0012024957, -0.0011475047, -0.02034081, 0.02422243],
            [-0.0485038805, 0.001567452, -0.0035707154, -0.0052990628, -0.01825681, 0.02087559],
        ];

        // When
        $B = self::$pls->getCoefficients()->getMatrix();

        // Then
        $this->assertEqualsWithDelta($expected, $B, .00001, '');
    }

    /**
     * @test The class returns the correct values for C
     *
     * R code for expected values:
     *   pls.model$C
     */
    public function testC()
    {
        // Given
        $expected = [
            [0.034970929,  0.02335423,  0.29614554,  0.152681650,  0.38391867],
            [0.033493121, -0.04624945,  0.36657446,  0.208617731, -0.04463526],
            [-0.006399525, -0.06849268, -0.21305926, -0.299013879,  0.13964463],
            [-0.056663579,  0.12247338,  0.08342433, -0.303325431,  0.03359633],
            [0.078737844, -0.09124596, -0.10004379, -0.003850354,  0.20090709],
            [-0.044316572,  0.15062065,  0.14929142, -0.001252483, -0.28604875],
        ];

        // When
        $C = self::$pls->getYLoadings()->getMatrix();

        // Then
        $this->assertEqualsWithDelta($expected, $C, .00001, '');
    }
             
    /**
     * R code for expected values:
     * X = cereal$X[1,]
     * (X - colMeans(cereal$X)) %*% solve(diag(apply(cereal$X, 2, sd))) %*% pls.model$B %*% diag(apply(cereal$Y, 2, sd)) + colMeans(cereal$Y)
     *
     * @test         predict Y values from X
     * @dataProvider dataProviderForRegression
     * @param        array $X
     * @param        array $Y
     */
    public function testRegression($X, $expected)
    {
        // Given.
        $input = MatrixFactory::create($X);

        // When
        $actual = self::$pls->predict($input)->getMatrix();

        // Then
        $this->assertEqualsWithDelta($expected, $actual, .00001, '');
    }

    public function dataProviderForRegression()
    {
        $cereal   = new SampleData\Cereal();
        return [
            [
                $cereal->getXData()->getRow(0),
                [[18477.04, 41.52811, 6.57031, 1.900265, 60.26447, 2.297653]],
            ],
            [
                $cereal->getXData()->getRow(9),
                [[18213.15, 40.54223, 6.816377, 1.633618, 68.58449, 1.675997]],
            ]
        ];
    }

    /**
     * @test predict error if the input X columns do not match
     */
    public function testPredictDataColumnMisMatch()
    {
        // Given
        $X = MatrixFactory::create([[6, 160, 3.9, 2.62, 16.46]]);

        // Then
        $this->expectException(\MathPHP\Exception\BadDataException::class);

        // When
        $prediction = self::$pls->predict($X);
    }
}
