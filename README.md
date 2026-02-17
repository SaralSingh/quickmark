# QuickMark — Presence Register Engine

## Theme

Praman is a universal presence‑register engine. It allows any user to create a list of people, start a session, and record who was present or absent for that session.

This is **not** an attendance app for students. It is a generic system that works for any group: classes, teams, events, meetings, or workshops.

> Create list → Start session → Mark presence.

---

## Core Concepts

### List

A fixed group of people created by the user.
Examples:

* BCA 3rd Year – Section A
* Cricket Team
* Workshop Participants

### Session

A single occurrence where presence is recorded.
Examples:

* DBMS Lecture – 5 Feb 2026
* Morning Practice
* Team Meeting

### Presence

A record of whether a person was present or absent in a specific session.

---

## User Flow

### Account Creation

1. User signs up with name, email, password.
2. User logs in and sees an empty dashboard.
3. User creates the first List.

---

### Teacher Scenario (Classroom Roll Call)

1. Create List: "BCA 3rd Year - Section A".
2. Add student names to the list.
3. On lecture day, start a Session: "DBMS Lecture".
4. System shows all students with checkboxes.
5. Teacher marks who is present.
6. Save attendance.
7. Later, teacher can view the session record.

---

### Normal Person Scenario (Any Group)

1. Create List: "Cricket Team".
2. Add people.
3. Start Session: "Morning Practice".
4. Mark who came.
5. Save and view record later.

---

## Database Design (Initial Blueprint)

### users

* id
* name
* email
* password
* created_at

### lists

* id
* user_id (FK users)
* name
* created_at

### people

* id
* list_id (FK lists)
* name
* created_at

### sessions

* id
* list_id (FK lists)
* title
* session_date
* created_at

### presences

* id
* session_id (FK sessions)
* person_id (FK people)
* is_present (boolean)
* created_at

---

## Relationships

* One user → many lists
* One list → many people
* One list → many sessions
* One session → many presences
* One person → many presences across sessions

---

## System Rules

1. A person can have only **one** presence record per session.
2. A session belongs to only one list.
3. A person belongs to only one list.
4. Presence is always recorded by the session owner (teacher/admin), not by the people.

---

## API‑First Approach

Praman will be built as a Laravel API‑first system. UI will be added later. All core actions are data operations exposed through APIs.

---

## Versioning

This project starts as **Praman v2.0** — a rebuilt, properly engineered version of an earlier attendance Hub project, focused on clean database design and rule‑driven presence tracking.
