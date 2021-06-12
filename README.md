# Rozproszone Systemy Operacyjne
*Authors: Patryk Wylegała*

A project created for the 'Distributed Operating Systems' course (Rozproszone Systemy Operacyjne - RSO)

## Table of contents

- [Functional](#functional)
- [Cluster](#cluster)
- [Image](#image)
- [Support](#support)
- [App information](#app-information)

## Functional

* Account creation (profile with Name, Surname, Address, NIP, PESEL, avatar – uploaded with standarized size)
* Sessions in Redis
* Wall is similar to Facebook wall, but we have only one wall for all users, just with text (max 256 chars) and it shows only last 10 posts. The current content of wall is cached using Redis. After every update of the wall the cache is updated
* When a user sends some text to a wall, it will be added to a queue (RabbitMQ) for admin approval. Admin can check all the posts waiting in queue and can either approve (meaning the post will go to the wall) or reject it (the post will be discarded)

## Cluster

* The app is run on both web server RS1 and RS2, load-balanced with LVS using Keepalived
* Each App server is connect to Mysql Master for necessary queries (like updates, but also some selects), and to slave for other queries
* The architecture design looks like [this](https://www.lucidchart.com/publicSegments/view/ffeb14d8-100b-4555-beba-562c354bdd6e/image.png)

## Image

![alt tag](https://github.com/MrVaath/RSO/blob/master/uploads/img.png)

## Support

Contact me via email

> **pat.wylegala@gmail.com**

## App information

**Language**: _PHP_

**Bootstrap Framework**: _Flat UI_

**Semester**: _Summer_

**Year**: _2017_
