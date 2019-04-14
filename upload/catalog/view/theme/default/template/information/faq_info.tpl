<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
     <?php echo $description; ?>
  <?php if ($faqs) { ?>
  <div class="faq-list">
    <div class="faqs-content">
		<div class="faq-block">
    <?php foreach ($faqs as $faq) { ?>
			<div>
			<div class="faq-heading" ><?php echo $faq['title']; ?></div>
			<div class="faq-content" id="<?php echo $faq['faq_id']; ?>"><?php echo $faq['description']; ?></div>
			</div>
    <?php } ?>
		</div>
    </div>
  </div>
  <div class="pagination"><?php echo $pagination; ?></div>
  <?php } else { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <?php }?>
      
      <?php echo $content_bottom; ?></div>
     <script type="text/javascript"><!--
$('.faq-block .faq-heading').bind('click', function() {
	$(".faq-content").slideUp("slow");
	$(".faq-heading").removeClass('active');

	if ($(this).parent().find('.faq-content').is(":visible")) {
		$(this).parent().find('.faq-content').slideUp('slow');
	} else {
		$(this).parent().find('.faq-content').slideDown('slow');
		$(this).addClass('active');
	}
});
//--></script>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?> 