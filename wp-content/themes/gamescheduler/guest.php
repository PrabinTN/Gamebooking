<?php
/**
 * Template Name: Guest Page
 */

get_header();
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <h2 class="text-center mb-4">Select Your Booking Details</h2>

                    <div class="mb-3">
                        <label for="bookingDate" class="form-label">
                            <i class="fa-solid fa-calendar-days"></i> Select Booking Date:
                        </label>
                        <input type="text" id="bookingDate" class="form-control flatpickr" placeholder="Pick a date" />
                    </div>

                    <div class="mb-3">
                        <label for="numAdults" class="form-label">
                            <i class="fa-solid fa-user"></i> Number of Adults:
                        </label>
                        <input type="number" id="numAdults" class="form-control" min="1" value="1" />
                    </div>

                    <div class="mb-3">
                        <label for="numKids" class="form-label">
                            <i class="fa-solid fa-child"></i> Number of Kids:
                        </label>
                        <input type="number" id="numKids" class="form-control" min="0" value="0" />
                    </div>

                    <div class="mb-3">
                        <label for="bookingTime" class="form-label">
                            <i class="fa-solid fa-clock"></i> Select Time:
                        </label>
                        <select id="bookingTime" class="form-select">
                            <?php
                            // Generate all 24-hour time slots with 30-minute intervals
                            for ($hour = 0; $hour < 24; $hour++) {
                                for ($minute = 0; $minute < 60; $minute += 30) {
                                    $time = sprintf('%02d:%02d', $hour, $minute);
                                    echo "<option value=\"$time\">$time</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="text-center">
                        <button id="confirmGuest" class="btn btn-primary btn-lg">
                             Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Flatpickr -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    flatpickr('#bookingDate', {
        dateFormat: 'Y-m-d',  
        minDate: 'today',    
        maxDate: new Date().fp_incr(30), 
        disableMobile: true,  
    });
});
</script>

<?php get_footer(); ?>