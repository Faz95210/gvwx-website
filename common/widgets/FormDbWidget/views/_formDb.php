<?php use yii\web\View; ?>

<h3>
    <?php echo \Yii::t('frontend', 'Ajouter un ' . $this->params['table']); ?>
</h3>

<!--<form action="--><? //=$_SERVER['PHP_SELF'];?><!--">-->
<?php
//    foreach($this->params['rules'] as $field => $rules) {
//        echo "$field => " .json_encode($rules) . '<br>';
//    }
$fields = [];
if (isset($this->params['update'])) {
    $fields[] = 'id';
    echo "<input type='hidden' id='id' value='" . $this->params['update'] . "'/>";
}
foreach ($this->params['rules'] as $field => $rules) {
    if ($field === 'id' || $field === 'status' || $field === 'user_id' || $field === 'created_at' || $field === 'updated_at') continue;
    $fields[] = $field;
    echo "<div class='form-group'>";
    echo "<label> $field";
    if (in_array("exist", $rules)) {
        ?>
        <select name="<?php echo $field ?>" id="<?php echo $field ?>" class='select2 form-control'>
            <?php
            foreach ($this->params[$field] as $value) {
                echo "<option " . ($this->params['values'][$field] == $value->id ? 'selected' : '') . " name='" . $value->id . "' value='$value->id'>" . (isset($value->label) ? $value->label : $value->id) . "</option>";
            }
            ?>
        </select>
        <?php
    } else if (in_array("integer", $rules) || in_array("number", $rules)) {
        $value = "";
        if (isset($this->params['values']) && isset($this->params['values'][$field])) {
            $value = $this->params['values'][$field];
        }
        echo "<input class='form-control'" . (in_array("required", $rules) ? 'required aria-required' : '') . " id='$field' name='$field' type='number' value='$value'/>";
    } else if (in_array("string", $rules)) {
        $value = "";
        if (isset($this->params['values']) && isset($this->params['values'][$field])) {
            $value = $this->params['values'][$field];
        }
        echo "<input class='form-control'" . (in_array("required", $rules) ? 'required aria-required' : '') . " id='$field' name='$field' type='text' value='$value'/>";
    }

    echo "</label></div><br>";
}
?>


<button class="btn btn-primary"
        onclick='sendDatas(<?php echo json_encode($fields); ?>, "<?php echo $this->params['table']; ?>")'>Valider
</button>

<script>
    function onSuccess(data) {
        if (data['result'] == 1) {
            location.reload(true);
        }
    }

    function sendDatas(fields, table) {
        let params = {};
        let fieldsJSON = {};
        let canContinue = true;

        console.log(fields);
        for (let i = 0; i < fields.length; i++) {
            const fieldInput = document.getElementById(fields[i]);
            const value = fieldInput.value;
            if (!value && fieldInput.required) {
                canContinue = false;
                fieldInput.className += " parsley-error";
            } else if (fieldInput.className.indexOf("parsley-error")) {
                fieldInput.className = fieldInput.className.replace("parsley-error", "");
            }
            fieldsJSON[fields[i]] = value;
        }
        if (!canContinue) return;
        params['fields'] = fieldsJSON;
        params['table'] = table;
        $.post('index.php?r=site/addtodb',
            params,
            onSuccess,
            'json'
        );
    }
</script>
