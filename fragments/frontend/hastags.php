<figure data-source="<?php echo $this->getVar('source');?>" data-featherlight-ajax="index.php?hub=<?= $this->getVar('source');?>&amp;sid=<?= $this->getVar('source_id');?>" id="entry_<?= $this->getVar('id');?>">
  <?php if (!empty(($Image = $this->getVar('image')))) {?>
  <img src="<?=$Image;?>" alt="">
  <?php }?>
    
  <?php if (($Caption = $this->getVar('caption')) != '') { ?>
  <figcaption>
    <div>
      <p>
        <?php
          $Caption = nl2br($Caption);
          $Caption = preg_replace('/ +/', ' ', $Caption);
          $Caption = stripslashes($Caption);
          echo $Caption;
        ?>
      </p>
    </div>
  </figcaption>
  <?php } ?>
</figure>