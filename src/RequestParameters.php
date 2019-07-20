<?php

declare(strict_types=1);

namespace Lsv\Darksky;

trait RequestParameters
{
    public function excludeBlocks(array $blocks): self
    {
        $this->queryParameters['exclude'] = $blocks;

        return $this;
    }

    public function language(string $language): self
    {
        $this->queryParameters['lang'] = $language;

        return $this;
    }

    public function units(string $unit): self
    {
        $this->queryParameters['units'] = $unit;

        return $this;
    }

    protected function excludeAllowedBlocks(): callable
    {
        return static function (array $values) {
            foreach ($values as $value) {
                if (!in_array($value, ['currently', 'minutely', 'hourly', 'daily', 'alerts', 'flags'])) {
                    return false;
                }
            }

            return true;
        };
    }

    protected function languageAllowed(): callable
    {
        $languages = explode(',', Request::ALLOWED_LANGUAGES);

        return static function (string $value) use ($languages) {
            return in_array(
                $value,
                $languages,
                true
            );
        };
    }

    protected function unitsAllowed(): callable
    {
        return static function (string $value) {
            return in_array($value, ['auto', 'ca', 'uk2', 'us', 'si'], true);
        };
    }
}
