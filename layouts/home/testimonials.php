<?php $testimonials = DB::select_all('testimonials', 5); ?>
<?php $is_first_testimonial = true; ?>
<?php $is_first_dot = true; ?>
<?php $counter = 0; ?>

<div class="testimonials section">
  <div class="title">ماذا قالوا عنا ؟</div>
  <div class="container">
    <div class="content">
      <?php while ($testimonial = $testimonials->fetch_assoc()) : ?>
        <?php $testimonial_classes = $is_first_testimonial ? 'testimonial active' : 'testimonial'; ?>
        <div class="<?php echo $testimonial_classes; ?>">
          <div class=" picture">
            <img src="<?php echo $testimonial['image']; ?>">
            <div class="info">
              <div class="name"><?php echo $testimonial['name']; ?></div>
              <div class="job"><?php echo $testimonial['profession']; ?></div>
            </div>
          </div>
          <div class="quote"><?php echo $testimonial['content']; ?></div>
          <div class="m-info">
            <div class="name"><?php echo $testimonial['name']; ?></div>
            <div class="job"><?php echo $testimonial['profession']; ?></div>
          </div>
        </div>
        <?php $is_first_testimonial = false; ?>
      <?php endwhile; ?>
    </div>
    <div class="dots">
      <?php while ($counter < $testimonials->num_rows) : ?>
        <?php $dot_classes = $is_first_dot ? 'dot active' : 'dot'; ?>
        <span class="<?php echo $dot_classes; ?>" data-index="<?php echo $counter; ?>"></span>
        <?php $is_first_dot = false; ?>
        <?php $counter = $counter + 1; ?>
      <?php endwhile; ?>
    </div>
  </div>
</div>