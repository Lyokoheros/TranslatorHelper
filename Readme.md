# Translator Helper
An simple web app, to help translators (both hobbyist from fandoms and maybe even professional ones)

## Table of content
* [General info and usage](#General-info-and-usage)
* [Technologies](#Technologies)
* [Setup](#Setup)
* [Main Features(Modules)](#Main-Features)
* [App's Future](#App's-future)

## General info and usage
Being a translator sometimes connects with many repetitive and boring task, which could be automated. This app aims to solve that. Every module deals with different task, which could be helpfull in different translation projects. 
It works in browser but mainly for providing more user-friendly GUI, but performs most tasks locally. How to use modules will be explained in [Main Features](#Main-Features) section (firstly the very point of the module, what it do, and how to use it).

## Technologies
- PHP
- XAMPP

## Language version
For now the app is just in Polish, but next languages may be added in future versions.

## Setup
This app require's no installation, but requires XAMPP (or any program with such functions like XAMPP).
To use it just put the folder inside htdocs, launch XAMPP apache and go to http://localhost/TranslatorHelper (if You put it directly into htdocs folder, in other cases customize the link accordingly). On first run app will create and pupulate the database if needed.

## Main Features
### SubtitlesConnector
When Translating some videos it could be helpful to compare two language versions live in the video. 
This when this module comes to aid. When You have two subtitle files (in SRT format), when one is considered "base file" (it coul simple be transcription of the video) and "additional file" (usually the languege we translage into), the module merge them into 1 file containing lines from both version. 

To use it simply choose both files and put the name in input element below(without extension). Then file will be saved inside 'Outputs' folder with given name.
The files should have coresponding linenumbers and time stamps - those from base file will be used. If the line with the same number are not corresponding to each other the result wouldn't be correct, as module has no in-build mechanism to fix this. 

## App's Future
Currently app is at early development state and only first module is ready. 
Planned modules are:
- script builder - module for automaticaly preparing scripts to translate literaly text (or any with more or less plain format)
- song script builder - module for automaticaly preparing scripts for songs (with counted amount of syllables)
When amount of modules will increase it's possible that way of displaying modules will change