@extends('inc.app')

@section('title', 'NEBULA | Timetable Management')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h2 class="text-center mb-4">Timetable Management</h2>
            <hr>
            
            <!-- Spinner and Toast containers -->
            <div id="spinner-overlay" style="display:none;"><div class="lds-ring"><div></div><div></div><div></div><div></div></div></div>
            <div id="toastContainer" aria-live="polite" aria-atomic="true" style="position: fixed; top: 10px; right: 10px; z-index: 1000;"></div>

            <!-- Tab Navigation -->
            <ul class="nav nav-tabs mb-4" id="timetableTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="degree-tab" data-bs-toggle="tab" data-bs-target="#degree-timetable" type="button" role="tab" aria-controls="degree-timetable" aria-selected="true">
                        <i class="ti ti-graduation-cap"></i> Degree Programs
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="certificate-tab" data-bs-toggle="tab" data-bs-target="#certificate-timetable" type="button" role="tab" aria-controls="certificate-timetable" aria-selected="false">
                        <i class="ti ti-certificate"></i> Certificate Programs
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="timetableTabsContent">
                <!-- Degree Programs Tab -->
                <div class="tab-pane fade show active" id="degree-timetable" role="tabpanel" aria-labelledby="degree-tab">
                    <div id="degree-filters" class="mb-4">
                        <div class="mb-3 row align-items-center">
                            <label for="degree_location" class="col-sm-3 col-form-label fw-bold">Location<span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-select filter-param" id="degree_location" name="location" required>
                                    <option value="" selected disabled>Select Location</option>
                                    <option value="Welisara">Nebula Institute of Technology - Welisara</option>
                                    <option value="Moratuwa">Nebula Institute of Technology - Moratuwa</option>
                                    <option value="Peradeniya">Nebula Institute of Technology - Peradeniya</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row align-items-center">
                            <label for="degree_course" class="col-sm-3 col-form-label fw-bold">Course<span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-select filter-param" id="degree_course" name="course_id" required disabled>
                                    <option selected disabled value="">Select Course</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row align-items-center">
                            <label for="degree_intake" class="col-sm-3 col-form-label fw-bold">Intake<span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-select filter-param" id="degree_intake" name="intake_id" required disabled>
                                    <option selected disabled value="">Select Intake</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row align-items-center">
                            <label for="degree_semester" class="col-sm-3 col-form-label fw-bold">Semester<span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-select filter-param" id="degree_semester" name="semester" required disabled>
                                    <option selected disabled value="">Select Semester</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row align-items-center" id="moduleStatusRow" style="display: none;">
                            <label class="col-sm-3 col-form-label fw-bold">Module Status</label>
                            <div class="col-sm-9">
                                <div id="moduleStatus" class="alert alert-info mb-0">
                                    <i class="ti ti-info-circle"></i> Select a semester to load modules
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row align-items-center">
                            <label for="degree_start_date" class="col-sm-3 col-form-label fw-bold">Semester Start Date<span class="text-danger">*</span></label>
                            <div class="col-sm-4">
                                <input type="date" class="form-control" id="degree_start_date" name="start_date" required readonly>
                            </div>
                            <label for="degree_end_date" class="col-sm-2 col-form-label fw-bold">End Date<span class="text-danger">*</span></label>
                            <div class="col-sm-3">
                                <input type="date" class="form-control" id="degree_end_date" name="end_date" required readonly>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Degree Timetable Table -->
                    <div class="mt-4" id="degreeTimetableSection" style="display:none;">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0" id="degreeTimetableHeader"></h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <form id="degreeTimetableForm" method="POST" action="{{ route('timetable.store') }}">
                                        @csrf
                                        <input type="hidden" name="course_type" value="degree">
                                        <table class="table table-bordered text-center align-middle" style="min-width: 900px;">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 120px;">Time/Date</th>
                                                    <th>Monday</th>
                                                    <th>Tuesday</th>
                                                    <th>Wednesday</th>
                                                    <th>Thursday</th>
                                                    <th>Friday</th>
                                                    <th>Saturday</th>
                                                    <th>Sunday</th>
                                                </tr>
                                            </thead>
                                            <tbody id="degreeTimetableBody">
                                                <!-- Time slots will be dynamically generated -->
                                            </tbody>
                                        </table>
                                        <div class="text-center mt-4">
                                            <button type="button" class="btn btn-success me-2" id="addDegreeTimeSlot">
                                                <i class="ti ti-plus"></i> Add Time Slot
                                            </button>
                                            <button type="submit" class="btn btn-primary me-2">Save Degree Timetable</button>
                                            <button type="button" class="btn btn-info" id="downloadDegreePDF">
                                                <i class="ti ti-download"></i> Download PDF
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Certificate Programs Tab -->
                <div class="tab-pane fade" id="certificate-timetable" role="tabpanel" aria-labelledby="certificate-tab">
                    <div id="certificate-filters" class="mb-4">
                        <div class="mb-3 row align-items-center">
                            <label for="certificate_location" class="col-sm-3 col-form-label fw-bold">Location<span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-select filter-param" id="certificate_location" name="location" required>
                                    <option value="" selected disabled>Select Location</option>
                                    <option value="Welisara">Nebula Institute of Technology - Welisara</option>
                                    <option value="Mathara">Nebula Institute of Technology - Mathara</option>
                                    <option value="Peradeniya">Nebula Institute of Technology - Peradeniya</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row align-items-center">
                            <label for="certificate_course" class="col-sm-3 col-form-label fw-bold">Course<span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-select filter-param" id="certificate_course" name="course_id" required disabled>
                                    <option selected disabled value="">Select Course</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row align-items-center">
                            <label for="certificate_intake" class="col-sm-3 col-form-label fw-bold">Intake<span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-select filter-param" id="certificate_intake" name="intake_id" required disabled>
                                    <option selected disabled value="">Select Intake</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row align-items-center">
                            <label for="certificate_start_date" class="col-sm-3 col-form-label fw-bold">Course Start Date<span class="text-danger">*</span></label>
                            <div class="col-sm-4">
                                <input type="date" class="form-control" id="certificate_start_date" name="start_date" required>
                            </div>
                            <label for="certificate_end_date" class="col-sm-2 col-form-label fw-bold">End Date<span class="text-danger">*</span></label>
                            <div class="col-sm-3">
                                <input type="date" class="form-control" id="certificate_end_date" name="end_date" required>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Certificate Timetable Table -->
                    <div class="mt-4" id="certificateTimetableSection" style="display:none;">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0" id="certificateTimetableHeader"></h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <form id="certificateTimetableForm" method="POST" action="{{ route('timetable.store') }}">
                                        @csrf
                                        <input type="hidden" name="course_type" value="certificate">
                                        <table class="table table-bordered text-center align-middle" style="min-width: 900px;">
                                            <thead class="table-light" id="certificateTableHead">
                                                <tr>
                                                    <th style="width: 120px;">Time/Date</th>
                                                    <!-- Date columns will be dynamically generated -->
                                                </tr>
                                            </thead>
                                            <tbody id="certificateTimetableBody">
                                                <!-- Time slots will be dynamically generated -->
                                            </tbody>
                                        </table>
                                        <div class="text-center mt-4">
                                            <button type="button" class="btn btn-success me-2" id="addCertificateTimeSlot">
                                                <i class="ti ti-plus"></i> Add Time Slot
                                            </button>
                                            <button type="submit" class="btn btn-primary me-2">Save Certificate Timetable</button>
                                            <button type="button" class="btn btn-info" id="downloadCertificatePDF">
                                                <i class="ti ti-download"></i> Download PDF
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Degree Program Elements
    const degreeLocation = document.getElementById('degree_location');
    const degreeCourse = document.getElementById('degree_course');
    const degreeIntake = document.getElementById('degree_intake');
    const degreeSemester = document.getElementById('degree_semester');
    const degreeStartDate = document.getElementById('degree_start_date');
    const degreeEndDate = document.getElementById('degree_end_date');
    const degreeTimetableSection = document.getElementById('degreeTimetableSection');
    const degreeTimetableHeader = document.getElementById('degreeTimetableHeader');
    const degreeTimetableBody = document.getElementById('degreeTimetableBody');
    const addDegreeTimeSlot = document.getElementById('addDegreeTimeSlot');
    const moduleStatusRow = document.getElementById('moduleStatusRow');
    const moduleStatus = document.getElementById('moduleStatus');

    // Certificate Program Elements
    const certificateLocation = document.getElementById('certificate_location');
    const certificateCourse = document.getElementById('certificate_course');
    const certificateIntake = document.getElementById('certificate_intake');
    const certificateStartDate = document.getElementById('certificate_start_date');
    const certificateEndDate = document.getElementById('certificate_end_date');
    const certificateTimetableSection = document.getElementById('certificateTimetableSection');
    const certificateTimetableHeader = document.getElementById('certificateTimetableHeader');
    const certificateTableHead = document.getElementById('certificateTableHead');
    const certificateTimetableBody = document.getElementById('certificateTimetableBody');
    const addCertificateTimeSlot = document.getElementById('addCertificateTimeSlot');

    // Helper functions
    function resetAndDisable(select, placeholder) {
        if (select) {
            select.innerHTML = `<option selected disabled value="">${placeholder}</option>`;
            select.disabled = true;
        }
    }

    function populateDropdown(select, items, valueKey, textKey, placeholder) {
        select.innerHTML = `<option selected disabled value="">Select ${placeholder}</option>`;
        items.forEach(item => {
            select.innerHTML += `<option value="${item[valueKey]}">${item[textKey]}</option>`;
        });
        select.disabled = false;
    }

    function populateDropdownWithData(select, items, valueKey, textKey, placeholder) {
        select.innerHTML = `<option selected disabled value="">Select ${placeholder}</option>`;
        items.forEach(item => {
            select.innerHTML += `<option value="${item[valueKey]}" data-start-date="${item.startDate}" data-end-date="${item.endDate}">${item[textKey]}</option>`;
        });
        select.disabled = false;
    }

    function showSpinner(show) {
        document.getElementById('spinner-overlay').style.display = show ? 'flex' : 'none';
    }

    function showToast(title, message, bgColor) {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.style.backgroundColor = bgColor;
        toast.innerHTML = `
            <div class="toast-header"><strong class="me-auto">${title}</strong><button type="button" class="btn-close" data-bs-dismiss="toast"></button></div>
            <div class="toast-body">${message}</div>
        `;
        container.appendChild(toast);
        new bootstrap.Toast(toast).show();
        toast.addEventListener('hidden.bs.toast', () => toast.remove());
    }

    // Generate time slot row for degree programs
    function generateDegreeTimeSlotRow(slotIndex) {
        const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        const row = document.createElement('tr');
        
        const timeCell = document.createElement('td');
        const timeInput = document.createElement('input');
        timeInput.type = 'text';
        timeInput.className = 'form-control form-control-sm';
        timeInput.name = `degree_time_slots[${slotIndex}][time]`;
        timeInput.placeholder = 'e.g., 8.00-9.00';
        timeInput.required = true;
        timeCell.appendChild(timeInput);
        row.appendChild(timeCell);

        days.forEach(day => {
            const cell = document.createElement('td');
            const select = document.createElement('select');
            select.className = 'form-select form-select-sm';
            select.name = `degree_time_slots[${slotIndex}][${day}]`;
            select.innerHTML = '<option value="">Select Module</option>';
            
            // Add modules to dropdown if available
            if (window.availableModules && window.availableModules.length > 0) {
                window.availableModules.forEach(module => {
                    const option = document.createElement('option');
                    option.value = module.module_id;
                    option.textContent = module.full_name;
                    select.appendChild(option);
                });
            }
            
            cell.appendChild(select);
            row.appendChild(cell);
        });

        const actionCell = document.createElement('td');
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-danger btn-sm';
        removeBtn.innerHTML = '<i class="ti ti-trash"></i>';
        removeBtn.onclick = () => row.remove();
        actionCell.appendChild(removeBtn);
        row.appendChild(actionCell);

        return row;
    }

    // Generate time slot row for certificate programs
    function generateCertificateTimeSlotRow(slotIndex, dates) {
        const row = document.createElement('tr');
        
        const timeCell = document.createElement('td');
        const timeInput = document.createElement('input');
        timeInput.type = 'text';
        timeInput.className = 'form-control form-control-sm';
        timeInput.name = `certificate_time_slots[${slotIndex}][time]`;
        timeInput.placeholder = 'e.g., 8.00-9.00';
        timeInput.required = true;
        timeCell.appendChild(timeInput);
        row.appendChild(timeCell);

        dates.forEach(date => {
            const cell = document.createElement('td');
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control form-control-sm';
            input.name = `certificate_time_slots[${slotIndex}][${date}]`;
            input.placeholder = 'Module/Notes';
            cell.appendChild(input);
            row.appendChild(cell);
        });

        const actionCell = document.createElement('td');
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-danger btn-sm';
        removeBtn.innerHTML = '<i class="ti ti-trash"></i>';
        removeBtn.onclick = () => row.remove();
        actionCell.appendChild(removeBtn);
        row.appendChild(actionCell);

        return row;
    }

    // Generate date columns for certificate programs
    function generateCertificateDateColumns(startDate, endDate) {
        const dates = [];
        const currentDate = new Date(startDate);
        const end = new Date(endDate);
        
        while (currentDate <= end) {
            dates.push(currentDate.toISOString().split('T')[0]);
            currentDate.setDate(currentDate.getDate() + 1);
        }

        const headerRow = certificateTableHead.querySelector('tr');
        headerRow.innerHTML = '<th style="width: 120px;">Time/Date</th>';
        
        dates.forEach(date => {
            const th = document.createElement('th');
            th.textContent = new Date(date).toLocaleDateString('en-GB');
            headerRow.appendChild(th);
        });

        return dates;
    }

    // Function to clear date field validation classes
    function clearDateValidation() {
        degreeStartDate.value = '';
        degreeEndDate.value = '';
        degreeStartDate.classList.remove('is-valid', 'is-invalid', 'is-loading');
        degreeEndDate.classList.remove('is-valid', 'is-invalid', 'is-loading');
    }

    // Degree Program Event Listeners
    degreeLocation.addEventListener('change', function() {
        resetAndDisable(degreeCourse, 'Select Course');
        resetAndDisable(degreeIntake, 'Select Intake');
        resetAndDisable(degreeSemester, 'Select Semester');
        degreeCourse.value = '';
        degreeIntake.value = '';
        degreeSemester.value = '';
        degreeTimetableSection.style.display = 'none';
        clearDateValidation();
        
        if (degreeLocation.value) {
            fetchCoursesByLocation(degreeLocation.value, 'degree', degreeCourse);
        }
    });

    degreeCourse.addEventListener('change', function() {
        resetAndDisable(degreeIntake, 'Select Intake');
        resetAndDisable(degreeSemester, 'Select Semester');
        degreeIntake.value = '';
        degreeSemester.value = '';
        clearDateValidation();
        
        if (degreeCourse.value && degreeLocation.value) {
            fetchIntakesByCourse(degreeCourse.value, degreeLocation.value, degreeIntake);
        }
    });

    degreeIntake.addEventListener('change', function() {
        resetAndDisable(degreeSemester, 'Select Semester');
        degreeSemester.value = '';
        clearDateValidation();
        
        if (degreeIntake.value && degreeCourse.value) {
            fetchSemesters(degreeCourse.value, degreeIntake.value, degreeSemester);
        }
    });

    degreeSemester.addEventListener('change', function() {
        if (degreeSemester.value) {
            // Get the selected semester data
            const selectedOption = degreeSemester.options[degreeSemester.selectedIndex];
            
            // Auto-fill start and end dates if available
            if (selectedOption.dataset.startDate && selectedOption.dataset.endDate) {
                // Show loading state briefly
                degreeStartDate.classList.add('is-loading');
                degreeEndDate.classList.add('is-loading');
                
                // Small delay to show loading state
                setTimeout(() => {
                    degreeStartDate.value = selectedOption.dataset.startDate;
                    degreeEndDate.value = selectedOption.dataset.endDate;
                    
                    // Remove loading state and add success state
                    degreeStartDate.classList.remove('is-loading');
                    degreeEndDate.classList.remove('is-loading');
                    degreeStartDate.classList.add('is-valid');
                    degreeEndDate.classList.add('is-valid');
                }, 300);
            }
            
            // Show module status row
            moduleStatusRow.style.display = 'block';
            moduleStatus.innerHTML = '<i class="ti ti-loader"></i> Loading modules...';
            
            // Fetch modules for the selected semester
            fetchModulesBySemester(degreeSemester.value);
            
            degreeTimetableSection.style.display = 'block';
            degreeTimetableHeader.style.display = 'block';
            updateDegreeTimetableHeader();
            
            // Add initial time slot
            degreeTimetableBody.innerHTML = '';
            degreeTimetableBody.appendChild(generateDegreeTimeSlotRow(0));
        } else {
            degreeTimetableSection.style.display = 'none';
            degreeTimetableHeader.style.display = 'none';
            moduleStatusRow.style.display = 'none';
        }
    });

    // Certificate Program Event Listeners
    certificateLocation.addEventListener('change', function() {
        resetAndDisable(certificateCourse, 'Select Course');
        resetAndDisable(certificateIntake, 'Select Intake');
        certificateCourse.value = '';
        certificateIntake.value = '';
        certificateTimetableSection.style.display = 'none';
        
        if (certificateLocation.value) {
            fetchCoursesByLocation(certificateLocation.value, 'certificate', certificateCourse);
        }
    });

    certificateCourse.addEventListener('change', function() {
        resetAndDisable(certificateIntake, 'Select Intake');
        certificateIntake.value = '';
        
        if (certificateCourse.value && certificateLocation.value) {
            fetchIntakesByCourse(certificateCourse.value, certificateLocation.value, certificateIntake);
        }
    });

    certificateIntake.addEventListener('change', function() {
        if (allCertificateFiltersFilled()) {
            certificateTimetableSection.style.display = 'block';
            certificateTimetableHeader.style.display = 'block';
            certificateTimetableHeader.innerHTML = 'Certificate Timetable for: ' + certificateCourse.options[certificateCourse.selectedIndex].text;
        } else {
            certificateTimetableSection.style.display = 'none';
            certificateTimetableHeader.style.display = 'none';
        }
    });

    // Date change listeners for certificate programs
    certificateStartDate.addEventListener('change', updateCertificateTable);
    certificateEndDate.addEventListener('change', updateCertificateTable);

    function updateCertificateTable() {
        if (certificateStartDate.value && certificateEndDate.value) {
            const dates = generateCertificateDateColumns(certificateStartDate.value, certificateEndDate.value);
            certificateTimetableBody.innerHTML = '';
            certificateTimetableBody.appendChild(generateCertificateTimeSlotRow(0, dates));
        }
    }

    // Add time slot buttons
    addDegreeTimeSlot.addEventListener('click', function() {
        const slotIndex = degreeTimetableBody.children.length;
        degreeTimetableBody.appendChild(generateDegreeTimeSlotRow(slotIndex));
    });

    addCertificateTimeSlot.addEventListener('click', function() {
        const slotIndex = certificateTimetableBody.children.length;
        const startDate = certificateStartDate.value;
        const endDate = certificateEndDate.value;
        if (startDate && endDate) {
            const dates = generateCertificateDateColumns(startDate, endDate);
            certificateTimetableBody.appendChild(generateCertificateTimeSlotRow(slotIndex, dates));
        }
    });

    // Function to regenerate all degree time slots with current modules
    function regenerateDegreeTimeSlots() {
        if (degreeTimetableBody.children.length > 0) {
            const currentSlots = degreeTimetableBody.children.length;
            degreeTimetableBody.innerHTML = '';
            for (let i = 0; i < currentSlots; i++) {
                degreeTimetableBody.appendChild(generateDegreeTimeSlotRow(i));
            }
        }
    }

    // Helper functions for checking if all filters are filled
    function allDegreeFiltersFilled() {
        return degreeLocation.value && degreeCourse.value && degreeIntake.value && degreeSemester.value && degreeStartDate.value && degreeEndDate.value;
    }

    function allCertificateFiltersFilled() {
        return certificateLocation.value && certificateCourse.value && certificateIntake.value && certificateStartDate.value && certificateEndDate.value;
    }

    // AJAX functions
    function fetchCoursesByLocation(location, courseType, targetSelect) {
        showSpinner(true);
        fetch(`/get-courses-by-location?location=${encodeURIComponent(location)}&course_type=${encodeURIComponent(courseType)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.courses && data.courses.length > 0) {
                    populateDropdown(targetSelect, data.courses, 'course_id', 'course_name', 'Course');
                } else {
                    resetAndDisable(targetSelect, 'Select Course');
                    showToast('Error', 'No courses found for this location and type.', 'bg-danger');
                }
            })
            .catch(() => {
                resetAndDisable(targetSelect, 'Select Course');
                showToast('Error', 'Failed to fetch courses.', 'bg-danger');
            })
            .finally(() => showSpinner(false));
    }

    function fetchIntakesByCourse(courseId, location, targetSelect) {
        showSpinner(true);
        fetch(`/get-intakes/${courseId}/${location}`)
            .then(response => response.json())
            .then(data => {
                if (data.intakes && data.intakes.length > 0) {
                    populateDropdown(targetSelect, data.intakes, 'intake_id', 'batch', 'Intake');
                } else {
                    resetAndDisable(targetSelect, 'Select Intake');
                    showToast('Error', 'No intakes found for this course and location.', 'bg-danger');
                }
            })
            .catch(() => {
                resetAndDisable(targetSelect, 'Select Intake');
                showToast('Error', 'Failed to fetch intakes.', 'bg-danger');
            })
            .finally(() => showSpinner(false));
    }

    function fetchSemesters(courseId, intakeId, targetSelect) {
        showSpinner(true);
        fetch(`/get-semesters?course_id=${encodeURIComponent(courseId)}&intake_id=${encodeURIComponent(intakeId)}`)
            .then(response => response.json())
            .then(data => {
                if (data.semesters && data.semesters.length > 0) {
                    const semesterOptions = data.semesters.map(s => ({ 
                        id: s.id, 
                        name: `${s.name} (${s.status})`,
                        startDate: s.start_date,
                        endDate: s.end_date
                    }));
                    populateDropdownWithData(targetSelect, semesterOptions, 'id', 'name', 'Semester');
                } else {
                    resetAndDisable(targetSelect, 'Select Semester');
                    showToast('Error', 'No active or upcoming semesters found for this course and intake.', 'bg-danger');
                }
            })
            .catch(() => {
                resetAndDisable(targetSelect, 'Select Semester');
                showToast('Error', 'Failed to fetch semesters.', 'bg-danger');
            })
            .finally(() => showSpinner(false));
    }

    // Function to update degree timetable header with module count
    function updateDegreeTimetableHeader() {
        const moduleCount = window.availableModules ? window.availableModules.length : 0;
        degreeTimetableHeader.innerHTML = 'Degree Timetable for: ' + degreeCourse.options[degreeCourse.selectedIndex].text + 
            ' - Semester ' + degreeSemester.options[degreeSemester.selectedIndex].text + 
            ` <span class="badge bg-info">${moduleCount} modules loaded</span>`;
    }

    // Function to fetch modules for a semester
    function fetchModulesBySemester(semesterId) {
        console.log('Fetching modules for semester ID:', semesterId);
        showSpinner(true);
        fetch(`/get-modules-by-semester?semester_id=${encodeURIComponent(semesterId)}`)
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Modules data received:', data);
                if (data.modules && data.modules.length > 0) {
                    window.availableModules = data.modules;
                    console.log('Available modules:', window.availableModules);
                    showToast('Success', `${data.modules.length} modules loaded.`, 'bg-success');
                    // Regenerate time slots with new modules
                    regenerateDegreeTimeSlots();
                    updateDegreeTimetableHeader();
                    moduleStatusRow.style.display = 'block';
                    moduleStatus.innerHTML = '<i class="ti ti-check-circle-2"></i> Modules loaded successfully!';
                } else {
                    window.availableModules = [];
                    console.log('No modules found for semester');
                    showToast('Warning', 'No modules found for this semester.', 'bg-warning');
                    // Regenerate time slots with empty modules
                    regenerateDegreeTimeSlots();
                    updateDegreeTimetableHeader();
                    moduleStatusRow.style.display = 'block';
                    moduleStatus.innerHTML = '<i class="ti ti-alert-circle"></i> No modules found for this semester.';
                }
            })
            .catch((error) => {
                console.error('Error fetching modules:', error);
                window.availableModules = [];
                showToast('Error', 'Failed to fetch modules.', 'bg-danger');
                // Regenerate time slots with empty modules
                regenerateDegreeTimeSlots();
                updateDegreeTimetableHeader();
                moduleStatusRow.style.display = 'block';
                moduleStatus.innerHTML = '<i class="ti ti-alert-circle"></i> Failed to load modules.';
            })
            .finally(() => showSpinner(false));
    }

    // Form submission handlers
    document.getElementById('degreeTimetableForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // Add form submission logic here
        showToast('Success', 'Degree timetable saved successfully!', 'bg-success');
    });

    document.getElementById('certificateTimetableForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // Add form submission logic here
        showToast('Success', 'Certificate timetable saved successfully!', 'bg-success');
    });

    // PDF Download handlers
    document.getElementById('downloadDegreePDF').addEventListener('click', function() {
        downloadTimetablePDF('degree');
    });

    document.getElementById('downloadCertificatePDF').addEventListener('click', function() {
        downloadTimetablePDF('certificate');
    });

    function downloadTimetablePDF(courseType) {
        // Get current filter values
        let filters = {};
        
        if (courseType === 'degree') {
            filters = {
                location: degreeLocation.value,
                course_id: degreeCourse.value,
                intake_id: degreeIntake.value,
                semester: degreeSemester.value,
                start_date: degreeStartDate.value,
                end_date: degreeEndDate.value
            };
        } else {
            filters = {
                location: certificateLocation.value,
                course_id: certificateCourse.value,
                intake_id: certificateIntake.value,
                start_date: certificateStartDate.value,
                end_date: certificateEndDate.value
            };
        }

        // Check if all required filters are filled
        const requiredFields = courseType === 'degree' 
            ? ['location', 'course_id', 'intake_id', 'semester', 'start_date', 'end_date']
            : ['location', 'course_id', 'intake_id', 'start_date', 'end_date'];

        const missingFields = requiredFields.filter(field => !filters[field]);
        
        if (missingFields.length > 0) {
            showToast('Error', 'Please fill all required fields before downloading PDF.', 'bg-danger');
            return;
        }

        // Show loading state
        showSpinner(true);
        
        // Create download URL with filters
        const params = new URLSearchParams({
            course_type: courseType,
            ...filters
        });
        
        const downloadUrl = `/download-timetable-pdf?${params.toString()}`;
        
        // Create temporary link and trigger download
        const link = document.createElement('a');
        link.href = downloadUrl;
        link.download = `${courseType}_timetable.pdf`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        showSpinner(false);
        showToast('Success', `${courseType.charAt(0).toUpperCase() + courseType.slice(1)} timetable PDF download started!`, 'bg-success');
    }

    // Tab coloring logic
    $('#timetableTabs .nav-link').on('shown.bs.tab', function (e) {
        $('#timetableTabs .nav-link').removeClass('bg-primary text-white');
        $(e.target).addClass('bg-primary text-white');
    });
});
</script>
@endpush

<style>
.lds-ring { display: inline-block; position: relative; width: 80px; height: 80px; }
.lds-ring div { box-sizing: border-box; display: block; position: absolute; width: 64px; height: 64px; margin: 8px; border: 8px solid #fff; border-radius: 50%; animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite; border-color: #fff transparent transparent transparent; }
.lds-ring div:nth-child(1) { animation-delay: -0.45s; }
.lds-ring div:nth-child(2) { animation-delay: -0.3s; }
.lds-ring div:nth-child(3) { animation-delay: -0.15s; }
@keyframes lds-ring { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
#spinner-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; z-index: 9999; }

/* Read-only date field styling */
input[type="date"][readonly] {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #495057;
}

/* Loading state for date fields */
input[type="date"].is-loading {
    background-color: #fff3cd;
    border-color: #ffeaa7;
    color: #856404;
}
</style>
@endsection