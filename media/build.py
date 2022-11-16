# coding=utf-8

import os, sys
import shutil
import urllib2
import subprocess
import signal
import socket
from tempfile import mkstemp
import zipfile
import time
import re

TARGET_PATH = os.path.dirname(os.path.abspath(sys.argv[0])) + '/build'
def target_path(suffix): return TARGET_PATH + '/' + (suffix or 'dev')
def target_widget_path(suffix): return target_path(suffix)
TARGET_PROXY_PATH = TARGET_PATH

BASE_WIDGET_PATH = os.path.dirname(os.path.abspath(sys.argv[0])) + '/widget2'
BASE_PROXY_PATH = os.path.dirname(os.path.abspath(sys.argv[0])) + '/apiproxy'

LMB_URL = u'http://62.76.188.14/'
API_URL = u'http://dev.lookmedbook.ru/widget_api/'

def copy_widget_files(suffix):
    if os.path.exists(target_path(suffix)):
        shutil.rmtree(target_path(suffix))

    if (target_path(suffix) != target_widget_path(suffix)):
        os.makedirs(target_path(suffix))

    #if not os.path.exists(TARGET_PROXY_PATH):
    #    os.makedirs(TARGET_PROXY_PATH)

    # copy widget
    def ignorePath(path):
      def ignore(p, files):
        return [f for f in files if os.path.abspath(os.path.join(p, f)) == os.path.normpath(path)]
      return ignore

    shutil.copytree(BASE_WIDGET_PATH, target_widget_path(suffix),
        ignore=ignorePath(BASE_WIDGET_PATH + '/html'))

def replace_in_file(file_path, regex, replacement):
     #Create temp file
    fh, abs_path = mkstemp()
    new_file = open(abs_path,'w')
    old_file = open(file_path, 'r')

    for line in old_file:
        new_file.write(re.sub(regex, replacement, line))

    #close temp file
    new_file.close()
    os.close(fh)
    old_file.close()
    #Remove original file
    os.remove(file_path)
    #Move new file
    shutil.move(abs_path, file_path)


def handle_samples(suffix, lmb_url):
    # replace url in sample.html
    FILES_FOR_REPLACING = [
        '/sample.html',
        '/sample_v2.html',
        '/sample_v2_layout.html',
        '/sample_mini.html',
        '/baby.html',
        '/sprosi.html',
        '/sites/sprosi.js',
        '/polismed.html',
        '/sites/polismed.js',
        '/piluli.html',
        '/extracted.js']

    for file_path in FILES_FOR_REPLACING:
        replace_in_file(target_widget_path(suffix) + file_path, r'var lmbUrl\s*=\s*".*"', 'var lmbUrl = "' + lmb_url + '"')
        replace_in_file(target_widget_path(suffix) + file_path, r'http://((192\.168\.\d+\.\d+)|(localhost)|(fake-lmb)|(partner))/widget2?/', lmb_url)
        

def handle_miniwd(suffix):
    replace_in_file(target_widget_path(suffix) + '/js/miniwd.js',
        r'apiURL\s*:\s".*?"', 'apiURL: "' + API_URL + '"')

def handle_css(suffix, pie_url):
    replace_in_file(target_widget_path(suffix) + '/css/main.css',
        r'behavior:\s*url.*;', 'behavior: url(' + pie_url + ');')

def build_proxy():
    # build proxy
    os.chdir(BASE_PROXY_PATH)
    subprocess.call(["c:/play-2.2.2/play.bat", "dist"])
    # copy proxy
    shutil.copyfile(BASE_PROXY_PATH + '/target/universal/apiproxy-1.0-SNAPSHOT.zip', TARGET_PROXY_PATH + '/apiproxy.zip')

def compile_js(suffix):
    # compile js from html
    path = target_widget_path(suffix) + '/js/compiled'
    if not os.path.exists(path):
        os.makedirs(path)

    import compile
    compile.compile(BASE_WIDGET_PATH + '/html', path)

    print "JS files compiled"

def build(suffix):
    lmb_url = LMB_URL + suffix + ('/' if suffix else '')
    pie_url = lmb_url + 'pie/PIE.htc'

    copy_widget_files(suffix)
    handle_samples(suffix, lmb_url)
    handle_miniwd(suffix)
    handle_css(suffix, pie_url)
    compile_js(suffix)

if os.path.exists(TARGET_PATH):
    shutil.rmtree(TARGET_PATH)

#build_proxy()
build('')
build('prod')

print "Done!"
