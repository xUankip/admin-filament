<?php

namespace Wiz\FilamentExtend\Forms\Components;

use Filament\Forms\Components\Select;
use Wiz\Helper\LocaleHelper;

class WizSelectLanguages extends Select
{
    protected function setUp(): void
    {
        parent::setUp();

        //$this->native(false);
        $this->searchable();
        $this->allowHtml();
        $this->options(LocaleHelper::getListOfLanguagesAsSelectOptions());
        $this->optionsLimit(1000);

        $recentLanguage = request()->cookie('recentLanguage',LocaleHelper::DEFAULT_LANGUAGE_NAME);

        //dump($recentLanguage);

        $this->default($recentLanguage);

        //$this->getSearchResultsUsing(fn (string $search): array => LocaleHelper::getListOfLanguagesAsSelectOptions($search));

    }
}
