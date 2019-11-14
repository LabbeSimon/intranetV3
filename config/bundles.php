<?php
/**
 * Copyright (C) 11 / 2019 | David annebicque | IUT de Troyes - All Rights Reserved
 * @file /Users/davidannebicque/htdocs/intranetv3/config/bundles.php
 * @author     David Annebicque
 * @project intranetv3
 * @date 14/11/2019 14:57
 * @lastUpdate 14/11/2019 14:56
 */

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class                => ['all' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class                          => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class                 => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class     => ['all' => true],
    Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle::class            => ['all' => true],
    Vich\UploaderBundle\VichUploaderBundle::class                        => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class                  => ['all' => true],
    FOS\JsRoutingBundle\FOSJsRoutingBundle::class                        => ['all' => true],
    Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle::class => ['all' => true],
    Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle::class    => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class                    => ['all' => true],
    Endroid\QrCodeBundle\EndroidQrCodeBundle::class                      => ['all' => true],
    Symfony\Bundle\MakerBundle\MakerBundle::class                        => ['dev' => true],
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class            => ['dev' => true, 'test' => true],
    Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class         => ['dev' => true, 'test' => true],
    Translation\Bundle\TranslationBundle::class                          => ['all' => true],
    Liip\ImagineBundle\LiipImagineBundle::class                          => ['all' => true],
];
