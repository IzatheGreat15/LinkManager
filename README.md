# LinkManager

Link Manager is a web-based application that manages your links for you.
Set a designated time for it to open and LinkManager will do it for you.

Never miss a single meeting again!


# LOGIC IN QUEUE
Update queue
    Query 1: Get all links that has their time >= to current time in ASCENDING order
    Query 2: Get all links that has their time < to current time in ASCENDING order (archive)
    Merge 2 queries

setInterval => to check whether current time is >= to set time of first entry in queue
if yes
    open link in another tab
    update queue

CRUD
Create
    add all fields
    update queue

Read
    update table

Edit
    edit all fields
    update queue

Delete
    delete entry
    update queue