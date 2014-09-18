//-*- mode:java; encoding:utf8n; coding:utf-8 -*-
// vim:set fileencoding=utf-8:
import java.awt.*;
import java.awt.datatransfer.*;
import java.awt.dnd.*;
import java.awt.event.*;
import java.awt.geom.*;
import java.awt.image.*;
import java.beans.*;
import javax.activation.*;
import javax.swing.*;
import javax.swing.event.*;
import javax.swing.table.*;

public class SharingTabsBetween2WindowsTest6 {
    private static JTable makeJTable() {
        String[] columnNames = {"String", "Integer", "Boolean"};
        Object[][] data = {
            {"AAA", 1, true}, {"BBB", 2, false},
        };
        TableModel model = new DefaultTableModel(data, columnNames) {
            @Override public Class<?> getColumnClass(int column) {
                return getValueAt(0, column).getClass();
            }
        };
        return new JTable(model);
    }
    private static JTabbedPane makeJTabbedPane(TransferHandler handler) {
        DnDTabbedPane tab = new DnDTabbedPane();
        tab.setTabLayoutPolicy(JTabbedPane.SCROLL_TAB_LAYOUT);
        tab.addTab("JTree  00", new JScrollPane(new JTree()));
        tab.addTab("JLabel 01", new JLabel("Test"));
        tab.addTab("JTable 02", new JScrollPane(makeJTable()));
        tab.addTab("JLabel 03", new JLabel("<html>asfasfdasdfasdfsa<br>asfdd13412341234123446745fgh"));
        tab.setTransferHandler(handler);
        return tab;
    }
    public static void main(String[] args) {
        EventQueue.invokeLater(new Runnable() {
            @Override public void run() {
                createAndShowGUI();
            }
        });
    }
    public static void createAndShowGUI() {
        try{
            UIManager.setLookAndFeel(UIManager.getSystemLookAndFeelClassName());
        } catch (Exception e) {
            e.printStackTrace();
        }
        TransferHandler handler = new TabTransferHandler();

        JFrame frame1 = new JFrame("main");
        frame1.setDefaultCloseOperation(WindowConstants.EXIT_ON_CLOSE);
        frame1.getContentPane().add(makeJTabbedPane(handler));
        frame1.setSize(320, 240);
        frame1.setLocationRelativeTo(null);

        Point pt = frame1.getLocation();
        pt.translate(340, 0);

        JFrame frame2 = new JFrame("sub");
        frame2.getContentPane().add(makeJTabbedPane(handler));
        frame2.setSize(320, 240);
        frame2.setLocation(pt);

        frame1.setVisible(true);
        frame2.setVisible(true);
    }
}

class DnDTabbedPane extends JTabbedPane {
    private static final int LINEWIDTH = 3;
    private final Rectangle lineRect = new Rectangle();
    public int dragTabIndex = -1;

    public static final class DropLocation extends TransferHandler.DropLocation {
        private final int index;
        private DropLocation(Point p, int index) {
            super(p);
            this.index = index;
        }
        public int getIndex() {
            return index;
        }
        private boolean dropable = true;
        public void setDropable(boolean flag) {
            dropable = flag;
        }
        public boolean isDropable() {
            return dropable;
        }
    }

    private void clickArrowButton(String actionKey) {
        ActionMap map = getActionMap();
        if(map != null) {
            Action action = map.get(actionKey);
            if(action != null && action.isEnabled()) {
                action.actionPerformed(new ActionEvent(this, ActionEvent.ACTION_PERFORMED, null, 0, 0));
            }
        }
    }
    public static Rectangle rBackward = new Rectangle();
    public static Rectangle rForward  = new Rectangle();
    private static int rwh = 20;
    private static int buttonsize = 30; //XXX 30 is magic number of scroll button size
    public void autoScrollTest(Point pt) {
        Rectangle r = getTabAreaBounds();
        int tabPlacement = getTabPlacement();
        if(tabPlacement==TOP || tabPlacement==BOTTOM) {
            rBackward.setBounds(r.x, r.y, rwh, r.height);
            rForward.setBounds(r.x+r.width-rwh-buttonsize, r.y, rwh+buttonsize, r.height);
        }else if(tabPlacement==LEFT || tabPlacement==RIGHT) {
            rBackward.setBounds(r.x, r.y, r.width, rwh);
            rForward.setBounds(r.x, r.y+r.height-rwh-buttonsize, r.width, rwh+buttonsize);
        }
        if(rBackward.contains(pt)) {
            clickArrowButton("scrollTabsBackwardAction");
        }else if(rForward.contains(pt)) {
            clickArrowButton("scrollTabsForwardAction");
        }
    }
    public DnDTabbedPane() {
        super();
        Handler h = new Handler();
        addMouseListener(h);
        addMouseMotionListener(h);
        addPropertyChangeListener(h);
    }
    private DropMode dropMode = DropMode.INSERT;
    public DropLocation dropLocationForPoint(Point p) {
        boolean isTB = getTabPlacement()==JTabbedPane.TOP || getTabPlacement()==JTabbedPane.BOTTOM;
        switch(dropMode) {
          case INSERT:
            Rectangle tar = getTabAreaBounds();
            Rectangle r;
            for(int i=0; i<getTabCount(); i++) {
                r = getBoundsAt(i);
                if(isTB) {
                    //r.translate(-r.width/2, 0);
                    r.x = r.x-r.width/2;
                    r.y = tar.y;
                    r.height = tar.height;
                }else{
                    //r.translate(0, -r.height/2);
                    r.x = tar.x;
                    r.y = tar.y-r.height/2;
                    r.width = tar.width;
                }
                if(r.contains(p)) return new DropLocation(p, i);
            }
            if(tar.contains(p)) return new DropLocation(p, getTabCount());
            break;
          case USE_SELECTION:
          case ON:
          case ON_OR_INSERT:
          default:
            assert false : "Unexpected drop mode";
        }
        return new DropLocation(p, -1);
    }
    private transient DropLocation dropLocation;
    public final DropLocation getDropLocation() {
        return dropLocation;
    }
    public Object setDropLocation(TransferHandler.DropLocation location, Object state, boolean forDrop) {
        Object retVal = null;
        DropLocation old = dropLocation;
        if(location instanceof DropLocation) {
            dropLocation = (DropLocation)location;
            firePropertyChange("dropLocation", old, dropLocation);
        }
        return retVal;
    }
    public void exportTab(int dragIndex, JTabbedPane target, int targetIndex) {
        System.out.println("exportTab");
        if(targetIndex<0) return;

        Component cmp    = getComponentAt(dragIndex);
        Container parent = target;
        while(parent!=null) {
            if(cmp==parent) return; //target==child: JTabbedPane in JTabbedPane
            parent = parent.getParent();
        }

        Component tab = getTabComponentAt(dragIndex);
        String str    = getTitleAt(dragIndex);
        Icon icon     = getIconAt(dragIndex);
        String tip    = getToolTipTextAt(dragIndex);
        boolean flg   = isEnabledAt(dragIndex);
        remove(dragIndex);
        target.insertTab(str, icon, cmp, tip, targetIndex);
        target.setEnabledAt(targetIndex, flg);
        ////ButtonTabComponent
        //if(tab instanceof ButtonTabComponent) tab = new ButtonTabComponent(target);
        target.setTabComponentAt(targetIndex, tab);
        target.setSelectedIndex(targetIndex);
        if(tab!=null && tab instanceof JComponent)
            ((JComponent)tab).scrollRectToVisible(tab.getBounds());
    }

    public void convertTab(int prev, int next) {
        System.out.println("convertTab");
        if(next<0 || prev==next) {
            return;
        }
        Component cmp = getComponentAt(prev);
        Component tab = getTabComponentAt(prev);
        String str    = getTitleAt(prev);
        Icon icon     = getIconAt(prev);
        String tip    = getToolTipTextAt(prev);
        boolean flg   = isEnabledAt(prev);
        int tgtindex  = prev>next ? next : next-1;
        remove(prev);
        insertTab(str, icon, cmp, tip, tgtindex);
        setEnabledAt(tgtindex, flg);
        //When you drag'n'drop a disabled tab, it finishes enabled and selected.
        //pointed out by dlorde
        if(flg) setSelectedIndex(tgtindex);
        //I have a component in all tabs (jlabel with an X to close the tab) and when i move a tab the component disappear.
        //pointed out by Daniel Dario Morales Salas
        setTabComponentAt(tgtindex, tab);
    }
    public void paintDropLine(Graphics g) {
        DropLocation loc = getDropLocation();
        if(loc == null || !loc.isDropable()) return;
        Graphics2D g2 = (Graphics2D)g;
        g2.setComposite(AlphaComposite.getInstance(AlphaComposite.SRC_OVER, 0.5f)); //java 1.7.0
        g2.setColor(Color.RED);
        Rectangle r = SwingUtilities.convertRectangle(this, getDropLineRect(loc), getRootPane().getGlassPane());
        g2.fill(r);
    }
    public Rectangle getDropLineRect(DnDTabbedPane.DropLocation loc) {
        int index = loc.getIndex();
        if(index<0) {
            lineRect.setRect(0,0,0,0);
            return lineRect;
        }
        //Point pt = loc.getDropPoint();
        boolean isZero = index==0;
        Rectangle r = getBoundsAt(isZero?0:index-1);
        if(getTabPlacement()==JTabbedPane.TOP || getTabPlacement()==JTabbedPane.BOTTOM)
            lineRect.setRect(r.x-LINEWIDTH/2+r.width*(isZero?0:1), r.y,LINEWIDTH,r.height);
        else
            lineRect.setRect(r.x,r.y-LINEWIDTH/2+r.height*(isZero?0:1), r.width,LINEWIDTH);
        return lineRect;
    }
    public Rectangle getTabAreaBounds() {
        Rectangle tabbedRect = getBounds();
        int xx = tabbedRect.x;
        int yy = tabbedRect.y;
        Component c = getSelectedComponent();
        if(c==null) return tabbedRect;
        Rectangle compRect = getSelectedComponent().getBounds();
        int tabPlacement = getTabPlacement();
        if(tabPlacement==TOP) {
            tabbedRect.height = tabbedRect.height - compRect.height;
        }else if(tabPlacement==BOTTOM) {
            tabbedRect.y = tabbedRect.y + compRect.y + compRect.height;
            tabbedRect.height = tabbedRect.height - compRect.height;
        }else if(tabPlacement==LEFT) {
            tabbedRect.width = tabbedRect.width - compRect.width;
        }else if(tabPlacement==RIGHT) {
            tabbedRect.x = tabbedRect.x + compRect.x + compRect.width;
            tabbedRect.width = tabbedRect.width - compRect.width;
        }
        tabbedRect.translate(-xx,-yy);
        //tabbedRect.grow(2, 2);
        return tabbedRect;
    }

    private class Handler implements MouseInputListener, PropertyChangeListener { //, BeforeDrag
        private void repaintDropLocation(DropLocation loc) {
            Component c = getRootPane().getGlassPane();
            if(c instanceof GhostGlassPane) {
                GhostGlassPane glassPane = (GhostGlassPane)c;
                glassPane.setTargetTabbedPane(DnDTabbedPane.this);
                glassPane.repaint();
            }
        }
        // PropertyChangeListener
        @Override public void propertyChange(PropertyChangeEvent e) {
            String propertyName = e.getPropertyName();
            if("dropLocation".equals(propertyName)) {
                //System.out.println("propertyChange: dropLocation");
                repaintDropLocation(getDropLocation());
            }
        }
        // MouseListener
        @Override public void mousePressed(MouseEvent e) {
            DnDTabbedPane src = (DnDTabbedPane)e.getSource();
            if(src.getTabCount()<=1) {
                startPt = null;
                return;
            }
            Point tabPt = e.getPoint(); //e.getDragOrigin();
            int idx = src.indexAtLocation(tabPt.x, tabPt.y);
            //disabled tab, null component problem.
            //pointed out by daryl. NullPointerException: i.e. addTab("Tab",null)
            startPt = (idx<0 || !src.isEnabledAt(idx) || src.getComponentAt(idx)==null)?null:tabPt;
        }
        private Point startPt;
        int gestureMotionThreshold = DragSource.getDragThreshold();
        //private final Integer gestureMotionThreshold = (Integer)Toolkit.getDefaultToolkit().getDesktopProperty("DnD.gestureMotionThreshold");
        @Override public void mouseDragged(MouseEvent e)  {
            Point tabPt = e.getPoint(); //e.getDragOrigin();
            if(startPt!=null && Math.sqrt(Math.pow(tabPt.x-startPt.x, 2)+Math.pow(tabPt.y-startPt.y, 2))>gestureMotionThreshold) {
                DnDTabbedPane src = (DnDTabbedPane)e.getSource();
                TransferHandler th = src.getTransferHandler();
                dragTabIndex = src.indexAtLocation(tabPt.x, tabPt.y);
                th.exportAsDrag(src, e, TransferHandler.MOVE);
                lineRect.setRect(0,0,0,0);
                src.getRootPane().getGlassPane().setVisible(true);
                src.setDropLocation(new DropLocation(tabPt, -1), null, true);
                startPt = null;
            }
        }
        @Override public void mouseClicked(MouseEvent e)  {}
        @Override public void mouseEntered(MouseEvent e)  {}
        @Override public void mouseExited(MouseEvent e)   {}
        @Override public void mouseMoved(MouseEvent e)    {}
        @Override public void mouseReleased(MouseEvent e) {}
    }
}

//http://java-swing-tips.blogspot.com/2010/02/tabtransferhandler.html
class TabTransferHandler extends TransferHandler {
    private final DataFlavor localObjectFlavor;
    public TabTransferHandler() {
        System.out.println("TabTransferHandler");
        localObjectFlavor = new ActivationDataFlavor(DnDTabbedPane.class, DataFlavor.javaJVMLocalObjectMimeType, "DnDTabbedPane");
    }
    private DnDTabbedPane source = null;
    @Override protected Transferable createTransferable(JComponent c) {
        System.out.println("createTransferable");
        if(c instanceof DnDTabbedPane) source = (DnDTabbedPane)c;
        return new DataHandler(c, localObjectFlavor.getMimeType());
    }
    @Override public boolean canImport(TransferHandler.TransferSupport support) {
        //System.out.println("canImport");
        if(!support.isDrop() || !support.isDataFlavorSupported(localObjectFlavor)) {
            System.out.println("canImport:"+support.isDrop()+" "+support.isDataFlavorSupported(localObjectFlavor));
            return false;
        }
        support.setDropAction(TransferHandler.MOVE);
        TransferHandler.DropLocation tdl = support.getDropLocation();
        Point pt = tdl.getDropPoint();
        DnDTabbedPane target = (DnDTabbedPane)support.getComponent();
        target.autoScrollTest(pt);
        DnDTabbedPane.DropLocation dl = (DnDTabbedPane.DropLocation)target.dropLocationForPoint(pt);
        int idx = dl.getIndex();
        boolean isDropable = false;

//         DnDTabbedPane source = TabTransferHandler.source;
//         if(!isWebStart()) {
//             try{
//                 source = (DnDTabbedPane)support.getTransferable().getTransferData(localObjectFlavor);
//             }catch(Exception ex) {
//                 ex.printStackTrace();
//             }
//         }
        if(target==source) {
            //System.out.println("target==source");
            isDropable = target.getTabAreaBounds().contains(pt) && idx>=0 && idx!=target.dragTabIndex && idx!=target.dragTabIndex+1;
        }else{
            //System.out.format("target!=source\n  target: %s\n  source: %s", target.getName(), source.getName());
            if(source!=null && target!=source.getComponentAt(source.dragTabIndex)) {
                isDropable = target.getTabAreaBounds().contains(pt) && idx>=0;
            }
        }
        glassPane.setVisible(false);
        target.getRootPane().setGlassPane(glassPane);
        glassPane.setVisible(true);

        Component c = target.getRootPane().getGlassPane();
        c.setCursor(isDropable?DragSource.DefaultMoveDrop:DragSource.DefaultMoveNoDrop);
        if(isDropable) {
            support.setShowDropLocation(true);
            dl.setDropable(true);
            target.setDropLocation(dl, null, true);
            return true;
        }else{
            support.setShowDropLocation(false);
            dl.setDropable(false);
            target.setDropLocation(dl, null, false);
            return false;
        }
    }
//     private static boolean isWebStart() {
//         try{
//             javax.jnlp.ServiceManager.lookup("javax.jnlp.BasicService");
//             return true;
//         }catch(Exception ex) {
//             return false;
//         }
//     }
    private BufferedImage makeDragTabImage(DnDTabbedPane tabbedPane) {
        Rectangle rect = tabbedPane.getBoundsAt(tabbedPane.dragTabIndex);
        BufferedImage image = new BufferedImage(tabbedPane.getWidth(), tabbedPane.getHeight(), BufferedImage.TYPE_INT_ARGB);
        Graphics g = image.getGraphics();
        tabbedPane.paint(g);
        g.dispose();
        if(rect.x<0) {
            rect.translate(-rect.x,0);
        }
        if(rect.y<0) {
            rect.translate(0,-rect.y);
        }
        if(rect.x+rect.width>image.getWidth()) {
            rect.width = image.getWidth() - rect.x;
        }
        if(rect.y+rect.height>image.getHeight()) {
            rect.height = image.getHeight() - rect.y;
        }
        return image.getSubimage(rect.x,rect.y,rect.width,rect.height);
    }

    private static GhostGlassPane glassPane;
    @Override public int getSourceActions(JComponent c) {
        System.out.println("getSourceActions");
        DnDTabbedPane src = (DnDTabbedPane)c;
        if(glassPane==null) {
            c.getRootPane().setGlassPane(glassPane = new GhostGlassPane(src));
        }
        if(src.dragTabIndex<0) return TransferHandler.NONE;
        glassPane.setImage(makeDragTabImage(src));
        c.getRootPane().getGlassPane().setVisible(true);
        return TransferHandler.MOVE;
    }
    @Override public boolean importData(TransferHandler.TransferSupport support) {
        System.out.println("importData");
        if(!canImport(support)) return false;

        DnDTabbedPane target = (DnDTabbedPane)support.getComponent();
        DnDTabbedPane.DropLocation dl = target.getDropLocation();
        try{
            DnDTabbedPane source = (DnDTabbedPane)support.getTransferable().getTransferData(localObjectFlavor);
            int index = dl.getIndex(); //boolean insert = dl.isInsert();
            if(target==source) {
                source.convertTab(source.dragTabIndex, index); //getTargetTabIndex(e.getLocation()));
            }else{
                source.exportTab(source.dragTabIndex, target, index);
            }
            return true;
        }catch(UnsupportedFlavorException ufe) {
            ufe.printStackTrace();
        }catch(java.io.IOException ioe) {
            ioe.printStackTrace();
        }
        return false;
    }
    @Override protected void exportDone(JComponent src, Transferable data, int action) {
        System.out.println("exportDone");
        //((DnDTabbedPane)src).setDropLocation(null, null, false);
        //src.getRootPane().getGlassPane().setVisible(false);
        glassPane.setVisible(false);
        glassPane = null;
        source = null;
    }
}

class GhostGlassPane extends JPanel {
    private DnDTabbedPane tabbedPane;
    public GhostGlassPane(DnDTabbedPane tabbedPane) {
        this.tabbedPane = tabbedPane;
        setOpaque(false);
    }
    private BufferedImage draggingGhost = null;
    public void setImage(BufferedImage draggingGhost) {
        this.draggingGhost = draggingGhost;
    }
    public void setTargetTabbedPane(DnDTabbedPane tab) {
        tabbedPane = tab;
    }
    @Override public void paintComponent(Graphics g) {
        Graphics2D g2 = (Graphics2D)g;
        DnDTabbedPane.DropLocation dl = tabbedPane.getDropLocation();
        Point p = getMousePosition(true); //dl.getDropPoint();
        if(draggingGhost != null && dl != null && p != null) {
            g2.setComposite(AlphaComposite.getInstance(AlphaComposite.SRC_OVER, 0.5f));
            if(dl.isDropable()) {
                tabbedPane.paintDropLine(g2);
            }
            double xx = p.getX() - (draggingGhost.getWidth(this) /2d);
            double yy = p.getY() - (draggingGhost.getHeight(this)/2d);
            g2.drawImage(draggingGhost, (int)xx, (int)yy , this);
        }
    }
}
