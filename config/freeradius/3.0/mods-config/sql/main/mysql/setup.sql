# -*- NetITWorks Config -*-
##
## admin.sql -- MySQL commands for creating the RADIUS user.
##
##	WARNING: You should change 'localhost' and 'radpass'
##		 to something else.  Also update raddb/mods-available/sql
##		 with the new RADIUS password.
##

#
#   Create default administrator for NetItWorks
#
CREATE USER 'radius'@'localhost';
SET PASSWORD FOR 'radius'@'localhost' = PASSWORD('radpass');

#   SET PERMISSIONS
# The server can write to and read from all tables
#
#  i.e.
GRANT ALL on netitworks.* TO 'radius'@'localhost';