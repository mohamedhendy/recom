<?php

namespace App\Support;

/**
 * An Easy bill api helper class.
 *
 * TODO: This class might need refactor or relocation in future.
 */
class EasyBillHelper
{
    public static $runJobs = true;

    /**
     * @param string|null $salutationToConvert
     * @return string
     */
    public static function convertSalutationToString(?string $salutationToConvert): ?string
    {
        switch ($salutationToConvert) {
            case "0":
            case null:
                $salutation = '';
                break;
            case "1":
                $salutation = 'Herrn';
                break;
            case "2":
                $salutation = 'Frau';
                break;
            case "3":
                $salutation = 'Firma';
                break;
            case "4":
                $salutation = 'Herr & Frau';
                break;
            case "5":
                $salutation = 'Verheiratet';
                break;
            case "6":
                $salutation = 'Familie';
                break;
            default:
                $salutation = $salutationToConvert;
        }

        return $salutation;
    }

    /**
     * @param string|null $salutationToConvert
     * @return string
     */
    public static function convertSalutationToInt(?string $salutationToConvert): ?string
    {
        switch ($salutationToConvert) {
            case "Herrn":
                $salutation = '1';
                break;
            case "Frau":
                $salutation = '2';
                break;
            case "Firma":
                $salutation = '3';
                break;
            case "Herr & Frau":
                $salutation = '4';
                break;
            case "Verheiratet":
                $salutation = '5';
                break;
            case "Familie":
                $salutation = '6';
                break;
            default:
                $salutation = '0';
        }

        return $salutation;
    }

}
