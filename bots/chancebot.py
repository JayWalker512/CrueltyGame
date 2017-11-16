#!/usr/bin/python3

import requests
import random

apiKey = 'thNnGRqdSqPwyzpKEQCVLUrXoQmjBoQhVw7BTfdJsrpIAtcgFgvsxUSUFMbnWmkWhN7RhQoyZEpNHf6UbJ8eK4EfZg9LGPNE2jHlA58c0lZnt5Zz6E5JAXtWUvMH9Inc'

r = requests.get(url='http://cruelty.com/games/canPlay?apiKey=' + apiKey)

#Decide if we check using a 50/50 strategy.
randVal = random.randint(0,100)
check = "0"
if (randVal > 50):
	check = "1"

#Send the play
r = requests.get(url='http://cruelty.brandonfoltz.com/games/botPlay?c=' + check + '&apiKey=' + apiKey)

if (r.json()['content']['success'] == "true"):
	print('Cruelty play submitted successfully. c=' + check)
else:
	print('Failed to submit play!')