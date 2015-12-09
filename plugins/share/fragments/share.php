<?php $buttons = $this->getVar('buttons');?><div class="socialhub_share">
  <?php foreach($buttons as $key => $button) {?>
  <a class="shore-icon openModal" href="index.php?share=<?php echo $button['short'];?>&amp;rex_modal=1&amp;<?php echo $button['query'];?>" title="<?php echo $button['title'];?>">
    <?php if(strpos($button['icon'],0,3) === 'fa-' && $this->getVar('fontawesome') != false) {?><i class="rex-icon <?php echo $button['icon'];?>"></i><?php } else {?>
    <span class="share-short"><?php echo $button[($this->getVar('short')?'short':'title')];?></span><?php }?></a>
  <?php if(($delimitter = $this->getVar('delimitter')) && $button !== end($buttons)) {?><span class="share_delimitter"><?php echo $delimitter;?></span><?php }?>
  <?php }?>
</div>