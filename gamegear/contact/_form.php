<div id="contentWrapper" class="content">
    <div class="form-container">
        <h2 style="text-align: center; margin-top: 0;">Get In Touch</h2>
        <p style="text-align: center; margin-bottom: 25px;">Have an issue with a seller or a question about our certification process? Let us know.</p>

        <form id="contactForm" action="" method="post" onsubmit="return validateForm(event)">
            
            <div class="form-group">
                <label for="Sal">Salutation:</label>
                <select id="Sal" name="salutation">
                    <option disabled selected value> -- Select a Salutation -- </option>
                    <option value="Mr" <?= (isset($salutation) && $salutation == 'Mr') ? 'selected' : ''; ?>>Mr</option>
                    <option value="Ms" <?= (isset($salutation) && $salutation == 'Ms') ? 'selected' : ''; ?>>Ms</option>
                    <option value="Mrs" <?= (isset($salutation) && $salutation == 'Mrs') ? 'selected' : ''; ?>>Mrs</option>
                    <option value="Mdm" <?= (isset($salutation) && $salutation == 'Mdm') ? 'selected' : ''; ?>>Mdm</option>
                </select>
                <div id="salutationError" class="error"><?= $errors['salutation'] ?? ''; ?></div>
            </div>

            <div class="form-group">
                <label for="nam">Name:</label>
                <input type="text" id="nam" name="name" value="<?= htmlspecialchars($name ?? ''); ?>">
                <div id="nameError" class="error"><?= $errors['name'] ?? ''; ?></div>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? ''); ?>">
                <div id="emailError" class="error"><?= $errors['email'] ?? ''; ?></div>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($phone ?? ''); ?>">
                <div id="phoneError" class="error"><?= $errors['phone'] ?? ''; ?></div>
            </div>

            <div class="form-group">
                <label>Type of Enquiry:</label>
                <div class="checkbox-group">
                    <label class="checkbox-label"><input type="checkbox" name="enquiry[]" value="General Enquiry" <?= in_array('General Enquiry', $enquiry ?? []) ? 'checked' : ''; ?>> General Enquiry</label>
                    <label class="checkbox-label"><input type="checkbox" name="enquiry[]" value="Complaints" <?= in_array('Complaints', $enquiry ?? []) ? 'checked' : ''; ?>> Complaints</label>
                    <label class="checkbox-label"><input type="checkbox" name="enquiry[]" value="Suggestions" <?= in_array('Suggestions', $enquiry ?? []) ? 'checked' : ''; ?>> Suggestions</label>
                </div>
                <div id="enquiryError" class="error"><?= $errors['enquiry'] ?? ''; ?></div>
            </div>

            <div class="form-group">
                <label for="message">Subject:</label>
                <textarea id="message" name="message" rows="6"><?= htmlspecialchars($message ?? ''); ?></textarea>
                <div id="messageError" class="error"><?= $errors['message'] ?? ''; ?></div>
            </div>

            <!-- Changed to match the blue full-width button from your images -->
            <button type="submit" class="submit-btn">Send</button>
        </form>
    </div>
</div>