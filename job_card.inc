<?php
// job_card.inc
// Reusable job card and modal template.
// Expects the following variables to be set before including:
//   $toggle_id      — unique checkbox ID for this card's modal
//   $title          — job title
//   $reference      — job reference code
//   $location       — job location
//   $short_desc     — short description
//   $salary         — formatted salary string
//   $reporting_line — reporting line
//   $responsibilities — array of responsibility strings
//   $essential        — array of essential requirement strings
//   $preferable       — array of preferable requirement strings
?>


<div class="job-cards">
    <div class="job-card-contents">
        <input type="checkbox" id="<?php echo $toggle_id; ?>" class="modal-toggle" />

        <label for="<?php echo $toggle_id; ?>" class="job-card">
            <span id="span-title">
                <?php echo $title; ?>
            </span>
            <span class="location" style="display: block;">
                &copy; <?php echo $location; ?>
            </span>
            <span class="job-description" style="display: block;">
                <?php echo $short_desc; ?>
            </span>
        </label>

        <div class="job-modal">
            <label for="<?php echo $toggle_id; ?>" class="modal-bg" aria-label="modal background"></label>
            <div class="modal-content">

                <h2><?php echo $title; ?></h2>

                <h3><strong>Reference:</strong> <?php echo $reference; ?></h3>
                <h4><strong>Short Description:</strong> <?php echo $short_desc; ?></h4>
                <h4><strong>Salary:</strong> $<?php echo $salary; ?> AUD</h4>
                <h4><strong>Reporting Line:</strong> <?php echo $reporting_line; ?></h4>

                <hr>

                <h4><strong>Key Responsibilities:</strong></h4>
                <ul>
                    <?php foreach ($responsibilities as $item): ?>
                        <li><?php echo htmlspecialchars(trim($item)); ?></li>
                    <?php endforeach; ?>
                </ul>

                <h4><strong>Essential Requirements:</strong></h4>
                <ul>
                    <?php foreach ($essential as $item): ?>
                        <li><?php echo htmlspecialchars(trim($item)); ?></li>
                    <?php endforeach; ?>
                </ul>

                <h4><strong>Preferable Requirements:</strong></h4>
                <ul>
                    <?php foreach ($preferable as $item): ?>
                        <li><?php echo htmlspecialchars(trim($item)); ?></li>
                    <?php endforeach; ?>
                </ul>

                <div class="apply-row">
		<a href="apply.php?job_ref=<?php echo urlencode($reference); ?>" class="custom-button">Apply</a>
                    <!--Redundant Links due to apply buttons inside modals-->
                    <span class="job-ref">REF: <?php echo $reference; ?></span>
                </div>

                <label for="<?php echo $toggle_id; ?>" class="exit">&times;</label>

            </div>
        </div>

    </div>
</div>
