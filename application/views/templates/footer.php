</section>
<footer>
    <em>&copy; 2017</em>
</footer>
        <script src="<?=base_url('js/jquery-3.1.1.min.js'); ?>"></script>
        <script src="<?=base_url('js/bootstrap.min.js'); ?>"></script>
        <script src="<?=base_url('js/scripts.js'); ?>"></script>
        <?php if (!empty($scripts)): ?>
            <?php foreach ($scripts as $script): ?>
                <script src="<?php echo $script; ?>"></script>
            <?php endforeach; ?>
        <?php endif; ?>
    </body></html>