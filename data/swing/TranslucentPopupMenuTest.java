//package example;
//-*- mode:java; encoding:utf8n; coding:utf-8 -*-
// vim:set fileencoding=utf-8:
//@homepage@
import java.awt.*;
import javax.swing.*;

public class TranslucentPopupMenuTest {
  private final JComponent tree = new JTree();
  public JComponent makeUI() {
    JPopupMenu popup = new TranslucentPopupMenu();
    popup.add(new JMenuItem("Undo"));
    popup.add(new JMenuItem("Redo"));
    popup.addSeparator();
    popup.add(new JMenuItem("Cut"));
    popup.add(new JMenuItem("Copy"));
    popup.add(new JMenuItem("Paste"));
    popup.add(new JMenuItem("Delete"));

    tree.setComponentPopupMenu(popup);
    JPanel p = new JPanel(new BorderLayout());
    p.add(new JScrollPane(tree));
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
    JFrame f = new JFrame();
    f.setDefaultCloseOperation(WindowConstants.EXIT_ON_CLOSE);
    f.getContentPane().add(new TranslucentPopupMenuTest().makeUI());
    f.setSize(320, 240);
    f.setLocationRelativeTo(null);
    f.setVisible(true);
  }
}
//http://terai.xrea.jp/Swing/TranslucentPopupMenu.html
class TranslucentPopupMenu extends JPopupMenu {
  private static final Color ALPHA_ZERO = new Color(0, true);
  private static final Color POPUP_BACK = new Color(250,250,250,200);
  private static final Color POPUP_LEFT = new Color(230,230,230,200);
  private static final int LEFT_WIDTH = 24;
  @Override public boolean isOpaque() {
    return false;
  }
  @Override public Component add(Component c) {
    if(c instanceof JComponent) {
      ((JComponent)c).setOpaque(false);
    }
    return c;
  }
  @Override public JMenuItem add(JMenuItem menuItem) {
    menuItem.setOpaque(false);
    super.add(menuItem);
    return menuItem;
  }
  @Override public void show(Component c, int x, int y) {
    EventQueue.invokeLater(new Runnable() {
      @Override public void run() {
        Window p = SwingUtilities.getWindowAncestor(TranslucentPopupMenu.this);
        if(p!=null && p instanceof JWindow) {
          JWindow w = (JWindow)p;
          w.setBackground(ALPHA_ZERO);
//           System.out.format("HeavyWeightWindow: %s, JPopupMenu: %s\n", w.getName(), getName());
//           Container c = (Container)w.getContentPane();
//           while(c!=null && c instanceof JComponent) {
//             JComponent jc = (JComponent)c;
//             System.out.format("%s: %s\n", c.getClass().getName(), jc.isOpaque());
//             if(jc.isOpaque()) {
//               jc.setOpaque(false);
//             }
//             c = c.getParent();
//           }
        }
      }
    });
    super.show(c, x, y);
  }
  @Override protected void paintComponent(Graphics g) {
    Graphics2D g2 = (Graphics2D)g.create();
/*
    g2.setPaint(POPUP_LEFT);
    g2.fillRect(0,0,LEFT_WIDTH,getHeight());
    g2.setPaint(POPUP_BACK);
    g2.fillRect(LEFT_WIDTH,0,getWidth(),getHeight());
/*/
    g2.setPaint(new Color(200,200,200,100));
    g2.fillRect(0,0,getWidth(),getHeight());
//*/
    g2.dispose();
  }
}