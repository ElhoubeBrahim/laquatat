<?php $results = DB::select_all('why_us'); ?>

<div class="why-us section">

  <div class="title">لماذا نحن ؟</div>

  <div class="container">
    <?php while ($result = $results->fetch_assoc()) : ?>
    <div class="box">
      <div class="icon">
        <i class="<?php echo $result['icon']; ?>"></i>
      </div>
      <div class="para">
        <?php echo $result['content']; ?>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
</div>