#!/usr/bin/env python3
import speech_recognition as sr
import pyttsx3
engine = pyttsx3.init()
engine.setProperty('rate', 200)
engine.setProperty('volume', 0.9)

r = sr.Recognizer()

speech = sr.Microphone()
with speech as source:    
     audio = r.adjust_for_ambient_noise(source)    
     audio = r.listen(source)
try:
    recog = r.recognize_google(audio, language="vi-VN")
    recog = recog.lower()

    if ('bật' in recog and 'đèn' in recog):
        print(1)
    elif ('tắt' in recog and 'đèn' in recog):
        print(0)
    
    if ('đỏ' in recog):
        print('r')
    elif ('xanh' in recog):
        print('g')

    if (('to' in recog and 'quạt' in recog) or ('bật' in recog and 'quạt' in recog)):
        print('max')
    elif ('tắt' in recog and 'quạt' in recog):
        print('min')
    elif ('nhỏ' in recog and 'quạt' in recog):
        print('medium')
    
except sr.UnknownValueError:
    print("I don't understand !")
    # engine.say("I don't understand")    
    # engine.runAndWait()
except sr.RequestError as e:
    print("Request error !")
    # engine.say("Request error")
    # engine.runAndWait()
