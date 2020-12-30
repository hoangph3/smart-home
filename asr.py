import speech_recognition as sr
#import pyttsx3
# engine = pyttsx3.init()
# engine.setProperty('rate', 200)
# engine.setProperty('volume', 0.9)

r = sr.Recognizer()

speech = sr.Microphone()
with speech as source:    
     audio = r.adjust_for_ambient_noise(source)    
     audio = r.listen(source)
try:    
    recog = r.recognize_google(audio, language = 'vi-VN')    
    print(recog)    
    # engine.say("You said: " + recog)    
    # engine.runAndWait()
except sr.UnknownValueError:
	print("I don't understand !")    
    # engine.say("I don't understand")    
    # engine.runAndWait()
except sr.RequestError as e:
	print("Request error !")    
    # engine.say("Request error")
    # engine.runAndWait()
