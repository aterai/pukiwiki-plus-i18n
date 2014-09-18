import java.awt.*;
import java.awt.event.*;
import javax.swing.*;
import javax.swing.table.*;

class TableDragScrollTest{
  public JComponent makeUI() {
    JTable table = new JTable(new DefaultTableModel(100, 3));
    Box panel = Box.createVerticalBox();
    for(int i=0;i<50;i++) panel.add(new JCheckBox("No."+i));
    MouseAdapter handler = new KineticScrollingListener3(table);
    table.addMouseMotionListener(handler);
    table.addMouseListener(handler);
    handler = new KineticScrollingListener3(panel);
    panel.addMouseMotionListener(handler);
    panel.addMouseListener(handler);
    JPanel p = new JPanel(new GridLayout(1, 2));
    p.add(new JScrollPane(table));
    p.add(new JScrollPane(panel));
    return p;
  }
  public static void main(String[] args) {
    EventQueue.invokeLater(new Runnable() {
      @Override public void run() {
        createAndShowGUI();
      }
    });
  }
  public static void createAndShowGUI() {
    JFrame frame = new JFrame();
    frame.setDefaultCloseOperation(WindowConstants.EXIT_ON_CLOSE);
    frame.getContentPane().add(new TableDragScrollTest().makeUI());
    frame.setSize(320, 240);
    frame.setLocationRelativeTo(null);
    frame.setVisible(true);
  }
}
class KineticScrollingListener3 extends MouseAdapter{
  private static final int SPEED = 6;
  private static final int DELAY = 10;
  private static final double D = 0.8;
  private final Cursor dc;
  private final Cursor hc = Cursor.getPredefinedCursor(Cursor.HAND_CURSOR);
  private final Timer scroller;
  private Point startPt = new Point();
  private Point delta   = new Point();

  public KineticScrollingListener3(final JComponent jc) {
    this.dc = jc.getCursor();
    this.scroller = new Timer(DELAY, new ActionListener() {
      @Override public void actionPerformed(ActionEvent e) {
        JViewport vport = (JViewport)jc.getParent();
        Point vp = vport.getViewPosition();
        vp.translate(-delta.x, -delta.y);
        jc.scrollRectToVisible(new Rectangle(vp, vport.getSize()));
        if(Math.abs(delta.x)>0 || Math.abs(delta.y)>0) {
          delta.setLocation((int)(delta.x*D), (int)(delta.y*D));
        }else{
          scroller.stop();
        }
      }
    });
  }
  @Override public void mouseDragged(MouseEvent e) {
    JComponent jc = (JComponent)e.getSource();
    Container c = jc.getParent();
    if(c instanceof JViewport) {
      JViewport vport = (JViewport)c;
      Point cp = SwingUtilities.convertPoint(jc,e.getPoint(),vport);
      Point vp = vport.getViewPosition();
      vp.translate(startPt.x-cp.x, startPt.y-cp.y);
      delta.setLocation(SPEED*(cp.x-startPt.x), SPEED*(cp.y-startPt.y));
      jc.scrollRectToVisible(new Rectangle(vp, vport.getSize()));
      startPt.setLocation(cp);
    }
  }
  @Override public void mousePressed(MouseEvent e) {
    JComponent jc = (JComponent)e.getSource();
    jc.setEnabled(false);
    Container c = jc.getParent();
    if(c instanceof JViewport) {
      jc.setCursor(hc);
      JViewport vport = (JViewport)c;
      Point cp = SwingUtilities.convertPoint(jc,e.getPoint(),vport);
      startPt.setLocation(cp);
      scroller.stop();
    }
  }
  @Override public void mouseReleased(MouseEvent e) {
    JComponent jc = (JComponent)e.getSource();
    jc.setCursor(dc);
    jc.setEnabled(true);
    scroller.start();
  }
}
