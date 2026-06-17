# SIMKAR - Product Requirements Document (PRD)

## Prison Room Transfer Management System

### 1. Product Overview

SIMKAR (Room Transfer Management System) is a web-based application designed to record, manage, and monitor inmate room transfers digitally.

### 2. Background

Room transfers are routine activities in prison operations. Manual record-keeping often causes difficulties in tracking transfer history, monitoring room occupancy, and generating reports. This system aims to centralize and digitize the entire process.

### 3. Objectives

- Digitize room transfer processes.
- Maintain structured transfer history records.
- Simplify transfer data searching.
- Improve room and inmate monitoring.
- Simplify report generation.
- Improve data accuracy and security.

### 4. User Roles

#### Administrator

Responsibilities:
- Manage users
- Manage inmate data
- Manage room data
- Manage transfers
- View reports
- Manage master data

#### Officer

Responsibilities:
- Create room transfers
- View inmate data
- View room data
- View transfer history
- Generate reports

### 5. Core Features

#### Authentication

- Login
- Logout
- Session management
- Role-based access control

#### Dashboard

Displays:
- Total active inmates
- Total rooms
- Occupied rooms
- Available rooms
- Today's transfers
- Monthly transfers
- Recent activities

#### Inmate Management

Features:
- Create inmate
- Update inmate
- Delete inmate
- Search inmates
- Filter inmates

#### Room Management

Features:
- Create room
- Update room
- Delete room
- Capacity monitoring

#### Room Transfer

Fields:
- Inmate
- Current Room
- Destination Room
- Transfer Date & Time
- Officer Name
- Notes
- Digital Signature

#### Transfer History

Features:
- Search
- Date filtering
- Officer filtering
- Transfer details

#### Reporting

Features:
- Period filtering
- PDF export
- Excel export

### 6. Business Flow

1. Officer logs into the system.
2. Opens the Room Transfer page.
3. Selects an inmate.
4. System automatically displays the current room.
5. Officer selects the destination room.
6. Officer fills in additional information.
7. Officer signs digitally.
8. System saves the transfer.
9. System updates the inmate's current room.
10. Transfer record becomes available in history and reports.

### 7. Non-Functional Requirements

#### Security

- Authentication required
- Encrypted passwords
- Role-based access control

#### Audit Trail

Store:
- Created by
- Updated by
- Created at
- Updated at

#### Responsiveness

- Desktop
- Tablet
- Mobile

### 8. Technology Stack

#### Backend

- Laravel

#### Frontend

- Laravel Livewire
- Blade
- Tailwind CSS
- Alpine.js

#### Database

- MySQL

#### Reporting

- PDF Export
- Excel Export

#### Digital Signature

- HTML5 Canvas Signature

#### Deployment

- Linux Server
- Apache / Nginx
- SSL / HTTPS

### 9. Minimum Viable Product (MVP)

- Authentication
- Dashboard
- Inmate Management
- Room Management
- Room Transfer
- Transfer History
- PDF Reports
- Excel Export
- Digital Signature

### 10. Future Enhancements

- Automated notifications
- Advanced analytics dashboard
- Transfer statistics charts
- Inmate location history
- WhatsApp integration
- Full audit logs
- QR code document verification
