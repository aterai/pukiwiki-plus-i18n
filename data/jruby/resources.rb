# -*- mode:ruby; Encoding:utf8n -*-
#module Example
include Java
import java.awt.BorderLayout
class MainPanel < javax.swing.JPanel
  def initialize
    super BorderLayout.new

    ####new javax.swing.ImageIcon("test.png");
    #icon = javax.swing.ImageIcon.new "test.png"
    #icon = javax.swing.ImageIcon.new "./test/test.png"
    #icon = javax.swing.ImageIcon.new ".\\test\\test.png"
    #icon = javax.swing.ImageIcon.new "c:/tmp/test.png"

    #new java.net.URL("http://terai.xrea.jp/data/swing/screenshots.png");
    #url = java.net.URL.new "http://terai.xrea.jp/data/swing/screenshots.png"
    #url = java.net.URL.new "file:/c:/tmp/test.png"
    #url = java.net.URL.new "file:///c:/tmp/test.png"
    #url = java.net.URL.new "file://localhost/c:/tmp/test.png"

    ####this.getClass().getResource("/test.png");
    url = self.get_class.get_resource "/test.png"
    #url = self.get_class.get_resource "/toolbarButtonGraphics/general/Copy24.gif"
    #url = self.get_class.get_resource "/test.png"

    ####file:/C:/tmp/org/jruby/javasupport/proxy/gen/test.png
    #url = self.get_class.get_resource "test.png"

    ####this.getClass().getClassLoader().getResource("test.png");
    #url = self.get_class.class_loader.get_resource "test.png"
    #url = self.get_class.class_loader.get_resource "toolbarButtonGraphics/general/Copy24.gif"
    #url = self.get_class.class_loader.get_resource "./Example/test.png"

    icon = javax.swing.ImageIcon.new url
    self.add javax.swing.JLabel.new(icon), BorderLayout::CENTER
    self.preferred_size = java.awt.Dimension.new 320, 240
  end
end
#end #module

import javax.swing.UIManager
import javax.swing.WindowConstants
def createAndShowGUI
  begin
    UIManager.look_and_feel = UIManager.system_look_and_feel_class_name
  rescue Exception => e
    proxied_e = JavaUtilities.wrap e.cause
    proxied_e.print_stack_trace
  end
  frame = javax.swing.JFrame.new "jruby swing"
  frame.default_close_operation = WindowConstants::EXIT_ON_CLOSE
  #frame.content_pane.add Example::MainPanel.new
  frame.content_pane.add MainPanel.new
  frame.pack
  frame.location_relative_to = nil
  frame.visible = true
end
class << r = java.lang.Runnable.new
  def run
    createAndShowGUI
  end
end
java.awt.EventQueue.invokeLater r
