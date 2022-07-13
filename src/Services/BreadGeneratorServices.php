<?php

namespace Desoft\DVoyager\Services;

use Desoft\DVoyager\Utils\Utilities;
use Exception;
use TCG\Voyager\Events\BreadAdded;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Models\DataType;

class BreadGeneratorServices {

    public function createBread($bread, $name){
        $requestedData = $this->generateRequestData($bread, $name);
        $dataType = Voyager::model('DataType');
        $res = $dataType->updateDataType($requestedData);
        $data = $res
            ? 'success'
            : 'error';
        if($dataType)
        {
            event(new BreadAdded($dataType, $data));
        }
    }

    private function generateRequestData(array $breadInfo, string $modelName)
    {
        $requestedDataArray = [];

        //DataType
        $requestedDataArray['name'] = $breadInfo['table'];
        $requestedDataArray['display_name_singular'] = ucfirst($breadInfo['single_name'] ?? $modelName);
        $requestedDataArray['display_name_plural'] = ucfirst($breadInfo['plural_name'] ?? $breadInfo['table']);
        $requestedDataArray['slug'] = strtolower(ucfirst($breadInfo['plural_name'] ?? $breadInfo['table']));
        $requestedDataArray['icon'] = null;
        $requestedDataArray['model_name'] = Utilities::generateClassNamespace($modelName);
        $requestedDataArray['controller'] = null;
        $requestedDataArray['policy_name'] = null;
        $requestedDataArray['generate_permissions'] = "on";
        $requestedDataArray['order_column'] = null;
        $requestedDataArray['order_display_column'] = null;
        $requestedDataArray['order_direction'] = "asc";
        $requestedDataArray['default_search_key'] = null;
        $requestedDataArray['description'] = null;

        $order = 0;

        //Add id data
        $requestedData['field_required_id'] = true;
        $requestedDataArray['field_id'] = 'id';
        $requestedDataArray['field_input_type_id'] = 'number';
        $requestedDataArray['field_details_id'] = '{}';
        $requestedDataArray['field_display_name_id'] = 'id';
        $requestedDataArray['field_order_id'] = $order++;

        //Add slug data
        $requestedData['field_required_slug'] = true;
        $requestedDataArray['field_slug'] = 'slug';
        $requestedDataArray['field_input_type_slug'] = 'text';
        $requestedDataArray['field_details_slug'] = '{}';
        $requestedDataArray['field_display_name_slug'] = 'slug';
        $requestedDataArray['field_order_slug'] = $order++;

        //DataRows
        foreach ($breadInfo['fields'] as $key => $value) {
            $requestedDataArray['field_required_'.$key] = !$value['isNullable'];
            $requestedDataArray['field_'.$key] = $key;
            $requestedDataArray['field_input_type_'.$key] = $value['voyager_type'] ?? 'text';
            $requestedDataArray['field_details_'.$key] = array_key_exists('validation', $value) ? $this->buildValidation($value['validation']) : '{}';
            $requestedDataArray['field_display_name_'.$key] = $value['display_name'] ?? $key;
            $requestedDataArray['field_order_'.$key] = $order++;
            $requestedDataArray['field_browse_'.$key] = true;
            $requestedDataArray['field_read_'.$key] = true;
            $requestedDataArray['field_edit_'.$key] = true;
            $requestedDataArray['field_add_'.$key] = true;
            $requestedDataArray['field_delete_'.$key] = true;
        }

        //Add created_at data
        $requestedData['field_required_created_at'] = true;
        $requestedDataArray['field_created_at'] = 'created_at';
        $requestedDataArray['field_input_type_created_at'] = 'timestamp';
        $requestedDataArray['field_details_created_at'] = '{}';
        $requestedDataArray['field_display_name_created_at'] = 'Fecha de creación';
        $requestedDataArray['field_order_created_at'] = $order++;
        $requestedDataArray['field_read_created_at'] = true;

        //Add updated_at data
        $requestedData['field_required_updated_at'] = true;
        $requestedDataArray['field_updated_at'] = 'updated_at';
        $requestedDataArray['field_input_type_updated_at'] = 'timestamp';
        $requestedDataArray['field_details_updated_at'] = '{}';
        $requestedDataArray['field_display_name_updated_at'] = 'Fecha de modificación';
        $requestedDataArray['field_order_updated_at'] = $order++;

        return $requestedDataArray;
    }

    public function buildValidation(array $validations)
    {
        if(!isset($validations['rule']))
            return '{}';

        return json_encode(['validation' => $validations]);
    }

}