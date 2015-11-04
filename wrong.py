import RPi.GPIO as GPIO
import time
pin = 11
def on(pin):
        GPIO.output(pin,True) ## Gets the pin and lights up the bulb (infinate)
	time.sleep(1)
        return

# making it last for "x seconds"
GPIO.setmode(GPIO.BOARD)#Gets all pins from raspberry pi
GPIO.setup(pin, GPIO.OUT)#Sets pin to 11

#Make this last for 5 seconds
for i in range(0,5):
       on(pin)

GPIO.cleanup()

