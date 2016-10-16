<?= $form->field($model->profile, 'birthday')->datetimeInput(['class' => 'span9',
    'type'=>'datetime','value'=>$model->profile['birthday']]); ?>