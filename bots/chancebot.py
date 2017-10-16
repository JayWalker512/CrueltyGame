#!/usr/bin/python3

import requests

r = requests.get(url='http://www.cruelty.com/games/botPlay')
print(r.content)