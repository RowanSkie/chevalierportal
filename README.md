# chevalierportal
Chevalier School Student Portal

### About

This repository holds Chevalier School Capstone Project: IT Group 2's Prototype.

Members:

Mark Joshua Zuniga (RowanSkie)

Deidrey Simon Paul Mandani

Joshua Edwin Cruz

Migel Sanchez

The main site can be checked by doing the following:
1. Download the file
2. Unzip the Workbench to a location of your choosing, may it be C:\Users\(USERNAME)\Documents
3. Download and Install XAMPP (https://www.apachefriends.org/download.html)
4. In XAMPP, do the following:
	a. disable MySQL and Tomcat during installation.
	b. Once downloaded and installed, select Config and "Apache (httpd.conf)"
	c. Change whatever is in DocumentRoot to "(Workbench folder location) and the Directory under it to the same directory.
5. Download and Install MySQL Workbench (https://dev.mysql.com/downloads/workbench/)
6. In MySQL Workbench, do the following:
	a. Select "Standalone MySQL Server/ Classic MySQL Replication"
	b. Skip the next step
	c. Select "Use Legacy AuthenticatioN Method (Retain MySQL 5.x Compatibility)
	d. The password to use is "Group2"
	e. Skip MySQL Router
	f. On Samples and Examples, type "Group2" on the password input area, press check.
	g. Run the remaining setup
7. Open MySQL Workbench and click Schemas from the left side.
8. Right click the window where "sakila", "sys" and "world" are located and click "Create Schema..."
9. Name it schoolportal.
10. Double click schoolportal schema and open up the file inside the SQL DB folder contained in this ZIP file using Notepad
11. Copy paste contents into the Query 1 window.
12. Press the Lightning bolt near the save button.
13. Right click schema window and click "Refresh All"