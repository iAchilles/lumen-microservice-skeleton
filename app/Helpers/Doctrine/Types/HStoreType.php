<?php
namespace App\Helpers\Doctrine\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;


/**
 * HStoreType class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
class HStoreType extends Type
{
    const HSTORE = 'hstore';

    const ESCAPE = '"\\';


    /**
     * @param array $fieldDeclaration
     * @param AbstractPlatform $platform
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform) : string
    {
        return 'hstore';
    }


    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return array
     */
    public function convertToPHPValue($value, AbstractPlatform $platform) : array
    {
        if (empty($value)) {
            return [];
        }

        $attributes = json_decode('{' . str_replace('"=>"', '":"', $value) . '}', true, 512, JSON_BIGINT_AS_STRING);
        if (json_last_error() != JSON_ERROR_NONE) {
            return [];
        }

        $array = [];

        foreach ($attributes as $key => $inputValue) {
            if (is_numeric($inputValue)) {
                if (false === strpos($inputValue, '.')) {
                    $inputValue = (int) $inputValue;
                } else {
                    $inputValue = (float) $inputValue;
                }
            } elseif (in_array(strtolower($inputValue), array('true', 'false'))) {
                $inputValue = $inputValue == 'true';
            }
            $array[$key] = $inputValue;
        }

        return $array;
    }


    /**
     * @param array $value
     * @param AbstractPlatform $platform
     * @return null|string
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return null;
        }
        if (!is_array($value)) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }
        $parts = [];

        foreach ($value as $key => $value) {
            $key = '"' . addcslashes($key, self::ESCAPE) . '"';
            $value = ($value === null ? 'NULL' : '"' . addcslashes($value, self::ESCAPE) . '"');
            $parts[] = $key . '=>' . $value;
        }

        return join(',', $parts);
    }


    /**
     * @return string
     */
    public function getName() : string
    {
        return self::HSTORE;
    }
}
