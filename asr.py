#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Wed Dec 16 20:16:32 2020

@author: hoangph3
"""

import warnings
warnings.filterwarnings('ignore')
import nemo.collections.asr as nemo_asr

quartznet = nemo_asr.models.EncDecCTCModel.from_pretrained(model_name="QuartzNet15x5Base-En")

Audio_sample = 'a.wav'

# Convert our audio sample to text
files = [Audio_sample]
raw_text = ''
for fname, transcription in zip(files, quartznet.transcribe(paths2audio_files=files)):
  raw_text = transcription
  
try:
    print("You: ", raw_text)
except:
    pass