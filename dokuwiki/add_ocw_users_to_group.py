"""Add a list of users to a group in the dokuwiki users.auth.php configuration file.

This is a helper script that will receive as input the path to the dokuwiki
`users.auth.php` configuration file, the path to a list of users and the group
the users should be added, and it will create a new file with the `-new` suffix.

Typical usage example:
    python3 add_ocw_users_to_group.py -ic users.auth.php -iu users_list -g uso
"""

import sys
import argparse
import os
import re

def readUsers(input_file):
    l = []
    with open(input_file) as f:
        for line in f:
            l.append(line.strip())
    return l

def main():
    parser = argparse.ArgumentParser(description='Add users to group in the users.auth.php file')
    parser.add_argument('-ic', '--input_conf_file', type=str, required=True,
                        help="Path to users.auth.php file")
    parser.add_argument('-iu', '--input_users_file', type=str, required=True,
                        help="Path to users file, each moodle user name on a new line")
    parser.add_argument('-g', '--group', type=str, required=True,
                        help="Group to be added. Ex: -g uso")
    args = parser.parse_args()

    users = readUsers(args.input_users_file)
    processed_users = []

    group = args.group

    with open(args.input_conf_file) as in_f, open(args.input_conf_file + "-new", "w") as out_f:
        count = 0
        for line in in_f:
            if re.search(".*:.*:.*:.*:.*", line):
                # This is a user line
                split = line.split(':')
                user = split[0]
                groups = split[-1].strip()
                if user in users:
                    processed_users.append(user)
                    if group in groups:
                        # User is already in group
                        out_f.write(line)
                        continue
                    new_groups = None
                    if groups == "user":
                        new_groups = groups
                    else:
                        new_groups = groups[0 : groups.find(",x")]
                        end = True
                    new_groups += "," + group + ",x"
                    new_user_line = user + ":" + ":".join(split[1:-1]) + ":" + new_groups + "\n"
                    out_f.write(new_user_line)
                else:
                    out_f.write(line)
            else:
                out_f.write(line)

        # Make sure all the users were added
        first = True
        for user in users:
            # Those users never logged in OCW, so they weren't previously found
            # Add the new, first time, users
            if user not in processed_users:
                if first == True:
                    out_f.write("\n# " + group + "\n\n")
                    first = False
                new_user_line = user + ":x:x:x:user," + group + ",x\n"
                out_f.write(new_user_line)

if __name__ == '__main__':
    main()
