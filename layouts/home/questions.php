<?php $questions = DB::select_all('questions', 3); ?>
<?php $is_first_question = true; ?>

<div class="questions section">

  <div class="title">الأسئلة الشائعة</div>

  <div class="container">
  <?php while ($question = $questions->fetch_assoc()) : ?>
    <?php $question_classes = $is_first_question ? 'question active' : 'question' ?>
    <div class="<?php echo $question_classes ?>">
      <div class="header">
        <div class="question-text"><?php echo $question['question']; ?></div>
        <i class="ri-arrow-down-s-line"></i>
      </div>
      <div class="content"><?php echo $question['answer']; ?></div>
    </div>
    <?php $is_first_question = false; ?>
    <?php endwhile; ?>
  </div>
</div>