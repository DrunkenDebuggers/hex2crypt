# Converts hash values in HEX representation to/from crypt() base64
# Based on http://stackoverflow.com/questions/461800/can-you-convert-the-output-of-php-crypt-to-valid-md5
#
# Test vectors
#
# 3ac3b4145aa7b9387a46dd7c780c1850 - CgCo33ebiHVuFhpwS.kMI0 
# 6f80dba665e27749ae88f58eaef5fe84 - Ps1PdaLWRoaiWDKCfjLyV1
# ec5f74086ec3fab34957d3ef0f838154 - v3xo04v1yfB7JxDj1sC/J/
#
#

from base64 import b64encode, b16decode
from string import maketrans
my_base64chars =  "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"
std_base64chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/"


def hex2crypt(hexhash):
    return b64encode(b16decode(hexhash, casefold=True)).translate(maketrans(std_base64chars, my_base64chars))

def crypt2hex(hexhash):
    return b16encode(b64decode(hexhash.translate(maketrans(my_base64chars, std_base64chars))))

if __name__ == "__main__":
    import sys

    mode=sys.argv[1]
    hexhash=sys.argv[2]

    if mode == "c":
        print hex2crypt(hexhash)
    elif mode == "x":
        print crypt2hex(hexhash)
    else:
        print "Possible modes:\n (x) crypt() to hex\n (c) hex() to crypt()"
