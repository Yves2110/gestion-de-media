<?php

namespace App\Support;

class DemoText
{
    public static function frenchParagraph(): string
    {
        return 'Ce document s\'inscrit dans une démarche de mutualisation des connaissances et de partage des ressources '
            . 'au profit des communautés locales. Il présente le contexte, les objectifs, la méthodologie retenue '
            . 'et les résultats observés sur le terrain. Les acteurs institutionnels, les partenaires techniques '
            . 'et les bénéficiaires ont contribué à enrichir cette publication par leurs retours d\'expérience. '
            . 'Les recommandations formulées visent à renforcer la résilience, améliorer l\'accès à l\'information '
            . 'et favoriser la participation citoyenne dans les territoires concernés.';
    }

    public static function words(int $count = 250): string
    {
        $paragraph = self::frenchParagraph();
        $words = [];
        while (count($words) < $count) {
            foreach (preg_split('/\s+/u', $paragraph, -1, PREG_SPLIT_NO_EMPTY) as $word) {
                $words[] = $word;
                if (count($words) >= $count) {
                    break 2;
                }
            }
        }

        return implode(' ', $words);
    }
}
