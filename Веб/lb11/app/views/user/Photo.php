<?php
$title = 'Мой фотоальбом';
?>

<script>
    let currentIndex = 0;

    function showLargePhoto(filename, caption, index) {
        currentIndex = index;
        const $largePhotoContainer = $("#large-photo-container");
        const $largePhotoFigure = $("#large-photo");
        $largePhotoFigure.empty();

        const $img = $("<img>").attr("src", filename).attr("alt", caption).attr("title", caption);
        const $figCap = $("<figcaption>").text(caption);

        $largePhotoFigure.append($img).append($figCap);
        $largePhotoContainer.removeClass("hidden");
    }

    function changePhoto(direction) {
        currentIndex = (currentIndex + direction + <?= count($photos) ?>) % <?= count($photos) ?>;
        const photo = <?= json_encode($photos) ?>[currentIndex];
        const $largePhotoFigure = $("#large-photo");
        $largePhotoFigure.fadeOut(300, function() {
            $largePhotoFigure.empty();

            const $img = $("<img>").attr("src", photo['filename']).attr("alt", photo['caption']).attr("title", photo['caption']);
            const $figCap = $("<figcaption>").text(photo['caption']);

            $largePhotoFigure.append($img).append($figCap);
            $largePhotoFigure.fadeIn(300);
        });
    }

    $(document).ready(function() {
        $("#large-photo-container-close-button").on("click", function() {
            $("#large-photo-container").addClass("hidden");
        });

        $("#next-photo").on("click", function() {
            changePhoto(1);
        });

        $("#prev-photo").on("click", function() {
            changePhoto(-1);
        });
    });
</script>

<?php if(isset($_SESSION['user']['fio'])):
    echo "<h1 class='page-title'>Пользователь: " . htmlspecialchars($_SESSION['user']['fio']) . "</h1>";
endif; ?>
<h1 class="page-title"><?= $title ?></h1>
<section class="page-box" id="photo-book">
    <div class="photo-container" id="main-photo-container">
        <?php foreach ($photos as $index => $photo): ?>
            <?php if ($index % 5 === 0): ?>
                <div class="photo-container-row">
                <?php endif; ?>
                <figure class="photo-container-el">
                    <img src="<?= $photo['filename'] ?>" alt="<?= $photo['caption'] ?>" onclick="showLargePhoto('<?= $photo['filename'] ?>', '<?= $photo['caption'] ?>', <?= $index ?>)">
                    <figcaption><?= $photo['caption'] ?></figcaption>
                </figure>
                <?php if (($index + 1) % 5 === 0 || $index === count($photos) - 1): ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>

<div id="large-photo-container" class="hidden">
    <div id="large-photo-container-close-button">
        <a>X</a>
    </div>
    <div class="large-photo-box">
        <button id="prev-photo">←</button>
        <figure id="large-photo"></figure>
        <button id="next-photo">→</button>
    </div>
</div>