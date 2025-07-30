<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $courseType }} Timetable</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .subtitle {
            font-size: 16px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .info-section {
            margin-bottom: 30px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            font-weight: bold;
            width: 150px;
            padding: 5px;
            border: 1px solid #ddd;
            background-color: #f8f9fa;
        }
        
        .info-value {
            display: table-cell;
            padding: 5px;
            border: 1px solid #ddd;
        }
        
        .timetable-section {
            margin-top: 30px;
        }
        
        .timetable-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
            background-color: #f8f9fa;
            padding: 10px;
            border: 1px solid #ddd;
        }
        
        .timetable-grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .timetable-grid th {
            background-color: #2c3e50;
            color: white;
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
            font-size: 11px;
        }
        
        .timetable-grid td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: middle;
            min-height: 40px;
        }
        
        .time-slot {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        
        .module-cell {
            background-color: #e8f5e8;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .modules-list {
            margin-top: 20px;
        }
        
        .modules-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            background-color: #f8f9fa;
            padding: 8px;
            border: 1px solid #ddd;
        }
        
        .module-item {
            padding: 5px;
            border-bottom: 1px solid #eee;
        }
        
        .module-code {
            font-weight: bold;
            color: #2c3e50;
        }
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">NEBULA INSTITUTE OF TECHNOLOGY</div>
        <div class="title">{{ $courseType }} Timetable</div>
        <div class="subtitle">Academic Year {{ date('Y') }}</div>
    </div>

    <div class="info-section">
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Course:</div>
                <div class="info-value">{{ $courseName }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Location:</div>
                <div class="info-value">{{ $location }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Intake:</div>
                <div class="info-value">{{ $intake }}</div>
            </div>
            @if(isset($semesterName))
            <div class="info-row">
                <div class="info-label">Semester:</div>
                <div class="info-value">{{ $semesterName }} ({{ $semesterStatus }})</div>
            </div>
            @endif
            <div class="info-row">
                <div class="info-label">Period:</div>
                <div class="info-value">{{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Generated:</div>
                <div class="info-value">{{ $generatedAt }}</div>
            </div>
        </div>
    </div>

    <div class="timetable-section">
        <div class="timetable-title">Weekly Timetable</div>
        
        <table class="timetable-grid">
            <thead>
                <tr>
                    <th style="width: 15%;">Time</th>
                    <th style="width: 12%;">Monday</th>
                    <th style="width: 12%;">Tuesday</th>
                    <th style="width: 12%;">Wednesday</th>
                    <th style="width: 12%;">Thursday</th>
                    <th style="width: 12%;">Friday</th>
                    <th style="width: 12%;">Saturday</th>
                    <th style="width: 12%;">Sunday</th>
                </tr>
            </thead>
            <tbody>
                <!-- Sample time slots - these would be populated with actual data -->
                <tr>
                    <td class="time-slot">08:00 - 09:00</td>
                    <td class="module-cell">Module A</td>
                    <td class="module-cell">Module B</td>
                    <td class="module-cell">Module C</td>
                    <td class="module-cell">Module D</td>
                    <td class="module-cell">Module E</td>
                    <td class="module-cell">Module F</td>
                    <td class="module-cell">Module G</td>
                </tr>
                <tr>
                    <td class="time-slot">09:00 - 10:00</td>
                    <td class="module-cell">Module B</td>
                    <td class="module-cell">Module C</td>
                    <td class="module-cell">Module D</td>
                    <td class="module-cell">Module E</td>
                    <td class="module-cell">Module F</td>
                    <td class="module-cell">Module G</td>
                    <td class="module-cell">Module A</td>
                </tr>
                <tr>
                    <td class="time-slot">10:00 - 11:00</td>
                    <td class="module-cell">Module C</td>
                    <td class="module-cell">Module D</td>
                    <td class="module-cell">Module E</td>
                    <td class="module-cell">Module F</td>
                    <td class="module-cell">Module G</td>
                    <td class="module-cell">Module A</td>
                    <td class="module-cell">Module B</td>
                </tr>
                <tr>
                    <td class="time-slot">11:00 - 12:00</td>
                    <td class="module-cell">Module D</td>
                    <td class="module-cell">Module E</td>
                    <td class="module-cell">Module F</td>
                    <td class="module-cell">Module G</td>
                    <td class="module-cell">Module A</td>
                    <td class="module-cell">Module B</td>
                    <td class="module-cell">Module C</td>
                </tr>
                <tr>
                    <td class="time-slot">12:00 - 13:00</td>
                    <td colspan="7" style="text-align: center; background-color: #ffe6e6; font-weight: bold;">LUNCH BREAK</td>
                </tr>
                <tr>
                    <td class="time-slot">13:00 - 14:00</td>
                    <td class="module-cell">Module E</td>
                    <td class="module-cell">Module F</td>
                    <td class="module-cell">Module G</td>
                    <td class="module-cell">Module A</td>
                    <td class="module-cell">Module B</td>
                    <td class="module-cell">Module C</td>
                    <td class="module-cell">Module D</td>
                </tr>
                <tr>
                    <td class="time-slot">14:00 - 15:00</td>
                    <td class="module-cell">Module F</td>
                    <td class="module-cell">Module G</td>
                    <td class="module-cell">Module A</td>
                    <td class="module-cell">Module B</td>
                    <td class="module-cell">Module C</td>
                    <td class="module-cell">Module D</td>
                    <td class="module-cell">Module E</td>
                </tr>
                <tr>
                    <td class="time-slot">15:00 - 16:00</td>
                    <td class="module-cell">Module G</td>
                    <td class="module-cell">Module A</td>
                    <td class="module-cell">Module B</td>
                    <td class="module-cell">Module C</td>
                    <td class="module-cell">Module D</td>
                    <td class="module-cell">Module E</td>
                    <td class="module-cell">Module F</td>
                </tr>
            </tbody>
        </table>
    </div>

    @if(isset($modules) && count($modules) > 0)
    <div class="modules-list">
        <div class="modules-title">Registered Modules for This Semester</div>
        @foreach($modules as $module)
        <div class="module-item">
            <span class="module-code">{{ $module['code'] }}</span> - {{ $module['name'] }}
        </div>
        @endforeach
    </div>
    @endif

    <div class="footer">
        <p>This timetable was generated on {{ $generatedAt }} by NEBULA Institute of Technology</p>
        <p>For any queries, please contact the academic office</p>
    </div>
</body>
</html> 