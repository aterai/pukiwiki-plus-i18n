include Java
import java.awt.Font
import java.awt.RenderingHints
import javax.swing.JComponent

class MainPanel < javax.swing.JPanel
  include java.awt.event.ActionListener
  def initialize
    super
    self.font = Font.new "serif", Font::PLAIN, 100
    @text = "JRuby Swing JPanel paintComponent Marquee Animation"
    @frc  = java.awt.font.FontRenderContext.new nil, true, true
    @tl   = java.awt.font.TextLayout.new @text, self.font, @frc
    @b    = @tl.bounds
    @yy   = @tl.ascent/2 + @b.y
    @xx   = 0
    javax.swing.Timer.new(10, self).start
    #icon = javax.swing.ImageIcon.new "test.png"
    #self.add javax.swing.JLabel.new(icon)
    self.preferred_size = java.awt.Dimension.new 320, 240
  end
  SuperPaint = JComponent.java_class.declared_method 'paintComponent', 'java.awt.Graphics'
  def paintComponent(g)
    #super.paintComponent g
    SuperPaint.invoke self.java_object, g.java_object
    g.setRenderingHint RenderingHints::KEY_TEXT_ANTIALIASING,
                       RenderingHints::VALUE_TEXT_ANTIALIAS_ON
    g.setRenderingHint RenderingHints::KEY_ANTIALIASING,
                       RenderingHints::VALUE_ANTIALIAS_ON
    g.color = java.awt.Color::WHITE
    g.drawLine 0, self.height/2, self.width, self.height/2
    g.color = java.awt.Color::BLACK
    g.font = self.font
    g.drawString @text, self.width-@xx, self.height/2-@yy
    @xx = (self.width+@b.width-@xx > 0) ? @xx+2 : 0
  end
  def actionPerformed(e)
    self.repaint
  end
end

import javax.swing.UIManager
import javax.swing.WindowConstants
def create_and_show_GUI
  begin
    UIManager.look_and_feel = UIManager.system_look_and_feel_class_name
  rescue Exception => e
    proxied_e = JavaUtilities.wrap e.cause
    proxied_e.print_stack_trace
  end
  frame = javax.swing.JFrame.new "JRuby Swing Marquee Animation"
  frame.default_close_operation = WindowConstants::EXIT_ON_CLOSE
  frame.content_pane.add MainPanel.new
  frame.pack
  frame.location_relative_to = nil
  frame.visible = true
end
def run; create_and_show_GUI; end
java.awt.EventQueue.invokeLater self
