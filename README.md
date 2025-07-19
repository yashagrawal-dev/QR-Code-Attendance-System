# QR Code Attendance System

A smart, web‑based attendance tracker built with Django and QR codes. Faculty display a QR code; students scan to mark attendance in real time. Ideal for classroom or event environments operating on the same local network.

---

## 🚀 Table of Contents

- [Features](#-features)  
- [Tech Stack](#-tech-stack)  
- [Demo](#-demo)  
- [Getting Started](#-getting-started)  
  - [Prerequisites](#prerequisites)  
  - [Installation](#installation)  
  - [Running the Server](#running-the-server)  
- [Usage](#-usage)  
- [Screenshots](#-screenshots)  
- [Contributing](#-contributing)  
- [License](#-license)  
- [Authors](#-authors)

---

## 🔍 Features

- **Automatic QR code generation** based on host machine IP.  
- **Faculty panel** to view and manage attendance—filter duplicates/proxies.  
- **Real-time attendance tracking** via live QR scans.  
- **Local network deployment**—ideal for classroom setups.  
- **Clean, intuitive UI** using HTML/CSS and Django backend.

---

## 🧰 Tech Stack

- **Backend**: Python 3, Django  
- **Frontend**: HTML, CSS, JavaScript  
- **QR Generation**: Python `qrcode` library  
- **Database**: SQLite (default Django setup)  
- **Web Server**: Django dev server (customizable IP/port)

---

## 🎬 Demo

Display QR code in class → students scan → attendance records populate instantly in faculty dashboard.

---

## 🧩 Getting Started

### Prerequisites

- Python 3.x  
- pip  
- Django  
- `qrcode` Python library  
- A modern web browser  

### Installation

```bash
git clone https://github.com/yashagrawal-dev/QR-Code-Attendance-System.git
cd QR-Code-Attendance-System
python3 -m venv venv
source venv/bin/activate       # On Windows use `venv\Scripts\activate`
pip install -r requirements.txt
