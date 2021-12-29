<?php
/*
 * Copyright (c) 2021. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/htdocs/intranetV3/src/Classes/Pdf/MyPDF.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 27/09/2021 09:08
 */

/*
 * Pull your hearder here, for exemple, Licence header.
 */

namespace App\Classes\Pdf;

use Carbon\Carbon;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MyPDF
{
    protected static array $options = [];

    protected static Environment $templating;

    private static Pdf $pdf;

    /**
     * MyPDF constructor.
     */
    public function __construct(Environment $templating, Pdf $pdf)
    {
        self::$templating = $templating;
        self::$pdf = $pdf;
        self::$options['enable-local-file-access'] = true; //https://yusufbiberoglu.medium.com/symfony-5-knpsnappybundle-wkhtmltopdf-setup-and-example-with-an-explanation-of-possible-errors-a890dbca238a

        $date = Carbon::now();

        self::setFooter([
            'footer-left' => escapeshellarg("Document généré depuis l'intranet le " . $date->format('d/m/Y H:i') . "."),
            'footer-right' => '[page] / [topage]',
            'footer-center' => '',
        ]);
    }

    public static function addOptions(string $key, string $value): void
    {
        //options disponibles : https://wkhtmltopdf.org/usage/wkhtmltopdf.txt
        self::$options[$key] = $value;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function generePdf($template, $data, $name, $departement = null): PdfResponse
    {
        return self::genereOutputPdf($template, $data, $name, $departement);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function genereAndSavePdf(
        string $template,
        array $data,
        string $name,
        string $dir,
        string $departement = null
    ): void {
        $output = self::genereOutputPdf($template, $data, $name, $departement);

        file_put_contents($dir . $name . '.pdf', $output);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private static function genereOutputPdf($template, $data, $name, $departement = null): PdfResponse
    {
        $html = self::$templating->render($template, $data);

        if ('.pdf' !== mb_substr($name, -4)) {
            $name .= '.pdf';
        }

        return new PdfResponse(
            self::$pdf->getOutputFromHtml($html, self::$options),
            $name
        );
    }

    public static function setFooter(array $data = [])
    {
        foreach ($data as $key => $d) {
            self::addOptions($key, $d);
        }
    }

    public static function setFooterHtml(string $template, array $data = [])
    {
        self::addOptions('footer-html', self::$templating->render($template, $data));
    }
}
