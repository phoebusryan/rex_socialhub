<?php
  $visible = $this->getVar('visible');
  $highlight = $this->getVar('highlight');
?><div class="rex-panel-options">
  <div class="btn-group btn-group-xs">
    <a data-state="<?php echo $visible?'visible':'invisible';?>" href="index.php?page=rex_socialhub/<?php echo $this->getVar('plugin');?>/toggle_entry&amp;entry=<?php echo $this->getVar('entry');?>&amp;state=<?php echo $visible?'visible':'invisible';?>" class="btn btn-<?php echo $visible?'visible':'invisible';?>" title="<?php echo $visible?'Deaktivieren':'Aktivieren';?>">
      <i class="rex-icon rex-icon-<?php echo $visible?'visible':'invisible';?>"></i>
    </a>
    <a data-state="<?php echo $highlight?'highlighted':'highlight';?>" href="index.php?page=rex_socialhub/<?php echo $this->getVar('plugin');?>/toggle_highlight&amp;entry=<?php echo $this->getVar('entry');?>&amp;state=<?php echo $highlight?'highlighted':'highlight';?>" class="btn btn-<?php echo $highlight?'highlighted':'highlight';?>" title="Hervorheben">
      <i class="rex-icon rex-icon-<?php echo $highlight?'highlighted':'highlight';?>"></i>
    </a>
  </div>
</div>