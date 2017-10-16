#!/usr/bin/python3

import requests
import random

apiKey = "thNnGRqdSqPwyzpKEQCVLUrXoQmjBoQhVw7BTfdJsrpIAtcgFgvsxUSUFMbnWmkWhN7RhQoyZEpNHf6UbJ8eK4EfZg9LGPNE2jHlA58c0lZnt5Zz6E5JAXtWUvMH9Inc"

#TODO need a way to determine if I can play yet

#Decide if we check using a 50/50 strategy.
randVal = random.randint(0,100)
check = "0"
if (randVal > 50):
	check = "1"

#Send the play
r = requests.get(url='http://cruelty.com/games/botPlay?c=' + check + '&api_key=' + apiKey)

print(r.json())

if (r.json()['content']['success'] == 1):
	print "Cruelty play submitted successfully. c=" + check
else:
	print "Failed to submit play!"