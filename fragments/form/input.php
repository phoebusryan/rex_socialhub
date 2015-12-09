<?php
$value = $this->getVar('value');
$classes = $this->getVar('classes');
$label = $this->getVar('label');
$name = $this->getVar('name');
$info = $this->getVar('info');
?><dl class="rex-form-group form-group controlls">
  <dt><label class="control-label" for="<?php echo rex_string::normalize($name,'');?>"><?php echo $label;?></dt>
  <dd>
    <input class="form-control<?php echo $classes?' '.$classes:'';?>" id="<?php echo rex_string::normalize($name,'');?>" type="text" name="<?php echo $name;?>" value="<?php echo $value?$value:'';?>">
    <?php if(!empty($info)) {?><div class="help-block"><?php echo $info;?></div><?php }?>
  </dd>
</dl>