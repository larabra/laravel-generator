<?php

namespace Larabra\Generator\Generators\Scaffold;

use Larabra\Generator\Common\CommandData;
use Larabra\Generator\Generators\BaseGenerator;
use Larabra\Generator\Generators\ModelGenerator;
use Larabra\Generator\Utils\FileUtil;
use Illuminate\Support\Str;

class RequestGenerator extends BaseGenerator
{

    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $path;

    /** @var string */
    private $fileName;

    /** @var string */
    private $updateFileName;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->path = $commandData->config->pathRequest;
        $this->fileName = $this->commandData->modelName.'Request.php';
    }

    public function generate()
    {
        $this->generateRequest();
    }

    private function generateRequest()
    {
        // add rules
        $rules = $this->generateRules();
        $rules[] = '';
        $rules = implode(','.infy_nl_tab(1, 3), $rules);
        $this->commandData->addDynamicVariable('$RULES$', $rules);
        
        $templateData = get_template('scaffold.request.request', 'laravel-generator');

        $templateData = fill_template($this->commandData->dynamicVars, $templateData);        

        FileUtil::createFile($this->path, $this->fileName, $templateData);

        $this->commandData->commandComment("\nCreate Request created: ");
        $this->commandData->commandInfo($this->fileName);
    }

    public function rollback()
    {
        if ($this->rollbackFile($this->path, $this->fileName)) {
            $this->commandData->commandComment('Request file deleted: '.$this->fileName);
        }
    }

    private function generateRules()
    {
        $dont_require_fields = config('larabra.laravel_generator.options.hidden_fields', [])
                + config('larabra.laravel_generator.options.excluded_fields', []);
        
        $rules = [];
        foreach ($this->commandData->fields as $field) {
            if (!$field->isPrimary && !in_array($field->name, $dont_require_fields)) {
                if ($field->isNotNull && empty($field->validations)) {
                    $field->validations = 'required';
                }
            }

            if (!empty($field->validations)) {
                if (Str::contains($field->validations, 'unique:')) {
                    $rule = explode('|', $field->validations);
                    // move unique rule to last
                    usort($rule, function ($record) {
                        return (Str::contains($record, 'unique:')) ? 1 : 0;
                    });
                    $field->validations = implode('|', $rule);
                }
                $rule = "\"$field->name\" => \"$field->validations\"";
                $rules[] = $rule;
            }
        }

        return $rules;
    }
}
